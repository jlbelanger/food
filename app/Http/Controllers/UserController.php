<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Extra;
use App\Models\Meal;
use App\Models\User;
use App\Models\Weight;
use DB;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Jlbelanger\Tapioca\Controllers\AuthorizedResourceController;
use Jlbelanger\Tapioca\Exceptions\JsonApiException;
use Jlbelanger\Tapioca\Exceptions\NotFoundException;
use Jlbelanger\Tapioca\Exceptions\ValidationException;
use Jlbelanger\Tapioca\Helpers\Utilities;
use Validator;

class UserController extends AuthorizedResourceController
{
	/**
	 * @param  Request $request
	 * @param  string  $id
	 * @return JsonResponse
	 */
	public function changeEmail(Request $request, string $id) : JsonResponse
	{
		$user = User::find($id);
		if ($user->username === 'demo') {
			throw JsonApiException::generate([['title' => 'You do not have permission to update this record.', 'status' => '403']], 403);
		}
		if (!$user || !Auth::guard('sanctum')->user()->can('update', $user)) {
			throw NotFoundException::generate();
		}

		$data = $request->input('data');
		$rules = [
			'attributes.password' => ['required'],
			'attributes.email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
		];
		$validator = Validator::make($data, $rules, [], Utilities::prettyAttributeNames($rules));
		if ($validator->fails()) {
			$errors = ValidationException::formatErrors($validator->errors()->toArray());
			return response()->json(['errors' => $errors], 422);
		}
		if (!empty($errors)) {
			return response()->json(['errors' => $errors], 422);
		}
		if (!Hash::check($data['attributes']['password'], $user->password)) {
			$error = [
				'title' => __('validation.current_password'),
				'source' => [
					'pointer' => '/data/attributes/password',
				],
				'status' => '422',
			];
			return response()->json(['errors' => [$error]], 422);
		}

		$user->email = $data['attributes']['email'];
		$user->save();

		return response()->json(null, 204);
	}

	/**
	 * @param  Request $request
	 * @param  string  $id
	 * @return JsonResponse
	 */
	public function changePassword(Request $request, string $id) : JsonResponse
	{
		$user = User::find($id);
		if ($user->username === 'demo') {
			throw JsonApiException::generate([['title' => 'You do not have permission to update this record.', 'status' => '403']], 403);
		}
		if (!$user || !Auth::guard('sanctum')->user()->can('update', $user)) {
			throw NotFoundException::generate();
		}

		$data = $request->input('data');
		$rules = [
			'attributes.password' => ['required'],
			'attributes.new_password' => ['required', 'confirmed', Rules\Password::defaults()],
			'attributes.new_password_confirmation' => ['required'],
		];
		$validator = Validator::make($data, $rules, [], Utilities::prettyAttributeNames($rules));
		if ($validator->fails()) {
			$errors = ValidationException::formatErrors($validator->errors()->toArray());
			return response()->json(['errors' => $errors], 422);
		}
		if (!empty($errors)) {
			return response()->json(['errors' => $errors], 422);
		}
		if (!Hash::check($data['attributes']['password'], $user->password)) {
			$error = [
				'title' => __('validation.current_password'),
				'source' => [
					'pointer' => '/data/attributes/password',
				],
				'status' => '422',
			];
			return response()->json(['errors' => [$error]], 422);
		}

		$user->forceFill([
			'password' => Hash::make($data['attributes']['new_password']),
		])->save();
		$user->setRememberToken(Str::random(60));
		event(new PasswordReset($user));

		return response()->json(null, 204);
	}

	/**
	 * @param  Request $request
	 * @return JsonResponse
	 */
	public function deleteData(Request $request) : JsonResponse
	{
		$types = $request->input('types');
		if (empty($types)) {
			return response()->json(['message' => 'Please select at least one type of data to delete.'], 422);
		}

		DB::beginTransaction();
		$user = Auth::guard('sanctum')->user();

		if (in_array('weights', $types)) {
			Weight::where('user_id', '=', $user->id)->delete();
		}

		if (in_array('meals', $types)) {
			Meal::where('user_id', '=', $user->id)->delete();
		}

		if (in_array('entries', $types)) {
			Entry::where('user_id', '=', $user->id)->delete();
			Extra::where('user_id', '=', $user->id)->delete();
		}

		DB::commit();

		return response()->json(null, 204);
	}
}
