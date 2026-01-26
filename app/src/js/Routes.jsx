import Calendar from './Pages/Calendar.jsx';
import Charts from './Pages/Charts.jsx';
import { createBrowserRouter } from 'react-router';
import EntryEdit from './Pages/Entries/Edit.jsx';
import Error404 from './Error404.jsx';
import FoodEdit from './Pages/Food/Edit.jsx';
import FoodList from './Pages/Food/List.jsx';
import FoodNew from './Pages/Food/New.jsx';
import ForgotPassword from './Pages/Auth/ForgotPassword.jsx';
import Layout from './Layout.jsx';
import Login from './Pages/Auth/Login.jsx';
import MealEdit from './Pages/Meals/Edit.jsx';
import MealList from './Pages/Meals/List.jsx';
import MealNew from './Pages/Meals/New.jsx';
import PrivateRoute from './Components/PrivateRoute.jsx';
import Profile from './Pages/Users/Edit.jsx';
import Register from './Pages/Auth/Register.jsx';
import ResetPassword from './Pages/Auth/ResetPassword.jsx';
import VerifyEmail from './Pages/Auth/VerifyEmail.jsx';

export default createBrowserRouter([
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
]);
