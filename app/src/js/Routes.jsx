import Calendar from './Pages/Calendar';
import Charts from './Pages/Charts';
import { createBrowserRouter } from 'react-router';
import Diary from './Pages/Diary';
import EntryEdit from './Pages/Entries/Edit';
import Error404 from './Error404';
import FoodEdit from './Pages/Food/Edit';
import FoodList from './Pages/Food/List';
import FoodNew from './Pages/Food/New';
import ForgotPassword from './Pages/Auth/ForgotPassword';
import Layout from './Layout';
import Login from './Pages/Auth/Login';
import MealEdit from './Pages/Meals/Edit';
import MealList from './Pages/Meals/List';
import MealNew from './Pages/Meals/New';
import PrivateRoute from './Components/PrivateRoute';
import Profile from './Pages/Users/Edit';
import Register from './Pages/Auth/Register';
import ResetPassword from './Pages/Auth/ResetPassword';
import VerifyEmail from './Pages/Auth/VerifyEmail';

export default createBrowserRouter(
	[
		{
			path: '/',
			Component: Layout,
			children: [
				{
					index: true,
					Component: Login,
				},
				{
					path: 'register',
					Component: Register,
				},
				{
					path: 'forgot-password',
					Component: ForgotPassword,
				},
				{
					path: 'reset-password/:token',
					Component: ResetPassword,
				},
				{
					path: 'verify-email',
					Component: VerifyEmail,
				},
				{
					path: '',
					Component: PrivateRoute,
					children: [
						{ path: 'calendar', Component: Calendar },
						{ path: 'charts', Component: Charts },
						{ path: 'entries/:id', Component: EntryEdit },
						{ path: 'profile', Component: Profile },
						{ path: 'food', Component: FoodList },
						{ path: 'food/new', Component: FoodNew },
						{ path: 'food/:id', Component: FoodEdit },
						{ path: 'meals', Component: MealList },
						{ path: 'meals/new', Component: MealNew },
						{ path: 'meals/:id', Component: MealEdit },
					],
				},
				{
					path: '*',
					Component: Error404,
				},
			],
		},
	]
);
