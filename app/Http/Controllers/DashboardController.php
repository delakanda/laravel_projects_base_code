<?php namespace App\Http\Controllers;

use App\Job;
use App\User;
use App\Role;
use App\Department;
use App\Employee;
use App\Application;
use App\Reminder;
use Auth;
use Validator;
use Input;
use Redirect;
use Session;
use Hash;
use Image;

class DashboardController extends Controller {


	public function index()
	{
		if(self::checkUserStatus())
		{
			$data['title'] = "Dashboard";

			if(self::checkUserPermissions("dashboard_department_can_view"))
			{
				$departmentsCount = Department::all()->count();
				$data['departmentsCount'] = $departmentsCount;
			}

			if(self::checkUserPermissions("dashboard_job_can_view"))
			{
				$jobCount = Job::all()->count();
				$data['jobCount'] = $jobCount;
			}

			if(self::checkUserPermissions("dashboard_employee_can_view"))
			{
				$employeeCount = Employee::all()->count();
				$data['employeeCount'] = $employeeCount;
			}

			if(self::checkUserPermissions("dashboard_application_can_view"))
			{
				$applicationCount = Application::all()->count();
				$data['applicationCount'] = $applicationCount;

				//check if there are interviews
				$interviewsCount = \DB::table("applications")->whereRaw("applicant_interview_date IS NOT NULL")->count();

				if($interviewsCount > 0)
				{
					$data['interviewsCount'] = $interviewsCount;
				}

				$applications = Application::all();
				$data['applications'] = $applications;
			}

			if(self::checkUserPermissions("dashboard_leave_can_view"))
			{
				$leaves = \DB::table("leaves")
					->where("leave_start_date","<=",new \DateTime(date('F jS Y h:i:s A')))
					->where("leave_end_date",">",new \DateTime(date('F jS Y h:i:s A')))
					->get();
				$data['leaves'] = $leaves;
			}

			$pendingReminders = \DB::table("reminders")->where("user_id",Auth::user()->id)->where("status","PENDING")->get();

			$data['reminders'] = Reminder::where("user_id",Auth::user()->id);
			if(count($pendingReminders) > 0)
			{
				$data['pendingReminders'] = $pendingReminders;
			}

			return view('dashboard.index',$data);
		}
	}

	public function profile()
	{
		$user = User::find(Auth::user()->id);

		$data['title'] = "My Profile";
		$data['user'] = $user;


		return view('dashboard.profile',$data);
	}

	public function saveProfile()
	{
		$user = User::find(Auth::user()->id);

		$rules = self::getRules();

		$validator = Validator::make(Input::all(), $rules);

		if ($validator->fails())
		{
			return Redirect::to('/dashboard/profile')
						->withErrors($validator)
						->withInput();
		}
		else
		{
			if(Input::get('password'))
			{
				if(Input::get('password') !== Input::get('confirm_password'))
				{
					return Redirect::to('/dashboard/profile')
	        		->withErrors("Passwords do not match")
	        		->withInput();
				}
				else
				{
					$user->password = Hash::make(Input::get('password'));
				}
			}
			//DEAL WITH IMAGE FILE
			if(Input::file(('image_name')))
			{
					if($user->image_name != null)
					{
						if (file_exists(public_path('uploads/'.$user -> image_name)))
						{
							unlink(public_path('uploads/'.$user -> image_name));
						}
					}

					$image = Input::file('image_name');

					$destinationImagePath = public_path('uploads/' . str_replace(" ","_",$image->getClientOriginalName()));

					$resizedImage = Image::make($image)->resize(200,200);

					$user -> image_name = str_replace(" ","_",$image->getClientOriginalName());

					$resizedImage -> save($destinationImagePath);

			}
			else
			{
				if(Input::get("clear_check") == 'yes')
				{
					if(file_exists(public_path('uploads/'.$user -> image_name)))
					{
						unlink(public_path('uploads/'.$user -> image_name));
					}
					$user->image_name = null;
				}
			}


			$user -> first_name = Input::get("first_name");
			$user -> last_name = Input::get("last_name");
			$user -> email = Input::get("email");
			$user -> username = Input::get("username");

			$user -> push();
			Session::flash('message', "Your Profile has been Updated");
			return Redirect::to("/dashboard/profile");
		}
	}

	public function changePassword()
	{
		$data['title'] = "Change Password";

		return view('auth.change_password',$data);
	}

	public function passwordChange()
	{
		$password = Input::get("password");
		$confirmPassword = Input::get("confirm_password");

		if(Input::get("password"))
		{
			if($password != $confirmPassword)
			{
				return Redirect::to('/dashboard/change_password')
							->withErrors("Passwords do not match");
			}
			else
			{
				$user = User::find(Auth::user()->id);
				$user -> password = Hash::make($password);
				$user -> status = 1;

				$user->push();

				Session::flash('message', "Your Password has been Changed");
				return Redirect::to("/dashboard");
			}
		}
		else
		{
			return Redirect::to('/dashboard/change_password')
						->withErrors("Passwords field required");
		}
	}

	public function getRules()
	{
		return array(
			'first_name' => 'required',
			'last_name' => 'required',
			'username' => 'required',
			'email' => 'required',
		);
	}

}
