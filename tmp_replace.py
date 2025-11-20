new_block = """    public function hrm_dashboard_index()
    {
        if (!Auth::check()) {
            if (!file_exists(storage_path() . '/installed')) {
                header('location:install');
                die;
            }
            $settings = Utility::settings();
            if ($settings['display_landing_page'] == 'on') {
                return view('layouts.landing');
            }
            return redirect('login');
        }

        if (!\\Auth::user()->can('show hrm dashboard')) {
            return $this->crm_dashboard_index();
        }

        $user = Auth::user();
        $currentDate = date('Y-m-d');
        $countUser = User::where('type', '!=', 'client')
            ->where('type', '!=', 'company')
            ->where('created_by', '=', \\Auth::user()->creatorId())
            ->count();

        $countTrainer = Trainer::where('created_by', '=', \\Auth::user()->creatorId())->count();
        $onGoingTraining = Training::where('status', '=', 1)->where('created_by', '=', \\Auth::user()->creatorId())->count();
        $doneTraining = Training::where('status', '=', 2)->where('created_by', '=', \\Auth::user()->creatorId())->count();

        $clientUsers = User::where('type', '=', 'client')->where('created_by', '=', \\Auth::user()->creatorId())->get();
        $countClient = count($clientUsers);
        $notClockIn = AttendanceEmployee::where('date', '=', $currentDate)->get()->pluck('employee_id');
        $notClockIns = Employee::where('created_by', '=', \\Auth::user()->creatorId())->whereNotIn('id', $notClockIn)->get();

        $activeJob = Job::where('status', 'active')->where('created_by', '=', \\Auth::user()->creatorId())->count();
        $inActiveJOb = Job::where('status', 'in_active')->where('created_by', '=', \\Auth::user()->creatorId())->count();

        $totalEmployees = Employee::where('created_by', '=', \\Auth::user()->creatorId())->count();
        $presentToday = AttendanceEmployee::where('date', '=', $currentDate)
            ->where('clock_in', '!=', '00:00:00')
            ->count();
        $absentToday = $totalEmployees - $presentToday;

        $totalOnLeave = Leave::where('status', 'Approved')
            ->where('start_date', '<=', $currentDate)
            ->where('end_date', '>=', $currentDate)
            ->where('created_by', '=', \\Auth::user()->creatorId())
            ->count();

        $pendingLeaves = Leave::where('status', 'Pending')
            ->where('created_by', '=', \\Auth::user()->creatorId())
            ->count();

        $totalDepartments = Department::where('created_by', '=', \\Auth::user()->creatorId())->count();
        $totalDesignations = Designation::where('created_by', '=', \\Auth::user()->creatorId())->count();

        $totalJobs = Job::where('created_by', '=', \\Auth::user()->creatorId())->count();
        $totalApplications = JobApplication::whereHas('job', function ($q) {
            $q->where('created_by', '=', \\Auth::user()->creatorId());
        })->count();

        $newHiresThisMonth = Employee::where('created_by', '=', \\Auth::user()->creatorId())
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->count();

        $upcomingBirthdays = Employee::where('created_by', '=', \\Auth::user()->creatorId())
            ->whereRaw('DATE_FORMAT(dob, "%m-%d") BETWEEN DATE_FORMAT(NOW(), "%m-%d") AND DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 7 DAY), "%m-%d")')
            ->count();

        $expiringDocuments = Document::where('created_by', '=', \\Auth::user()->creatorId())
            ->whereBetween('expiry_date', [$currentDate, date('Y-m-d', strtotime('+30 days'))])
            ->count();

        $last30Days = date('Y-m-d', strtotime('-30 days'));
        $totalAttendanceRecords = AttendanceEmployee::whereBetween('date', [$last30Days, $currentDate])
            ->where('clock_in', '!=', '00:00:00')
            ->count();
        $expectedAttendance = $totalEmployees * 30;
        $attendanceRate = $expectedAttendance > 0 ? round(($totalAttendanceRecords / $expectedAttendance) * 100, 1) : 0;

        $maleEmployees = Employee::where('created_by', '=', \\Auth::user()->creatorId())
            ->where('gender', 'Male')
            ->count();
        $femaleEmployees = Employee::where('created_by', '=', \\Auth::user()->creatorId())
            ->where('gender', 'Female')
            ->count();

        $departmentStats = Employee::select('department_id', \\DB::raw('count(*) as total'))
            ->where('created_by', '=', \\Auth::user()->creatorId())
            ->groupBy('department_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->with('department')
            ->get();

        $recentLeaves = Leave::where('created_by', '=', \\Auth::user()->creatorId())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->with('employee.user')
            ->get();

        $stats = [
            'onGoingTraining' => $onGoingTraining,
            'activeJob' => $activeJob,
            'inActiveJOb' => $inActiveJOb,
            'doneTraining' => $doneTraining,
            'countTrainer' => $countTrainer,
            'countClient' => $countClient,
            'countUser' => $countUser,
            'notClockIns' => $notClockIns,
            'totalEmployees' => $totalEmployees,
            'presentToday' => $presentToday,
            'absentToday' => $absentToday,
            'totalOnLeave' => $totalOnLeave,
            'pendingLeaves' => $pendingLeaves,
            'totalDepartments' => $totalDepartments,
            'totalDesignations' => $totalDesignations,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'newHiresThisMonth' => $newHiresThisMonth,
            'upcomingBirthdays' => $upcomingBirthdays,
            'expiringDocuments' => $expiringDocuments,
            'attendanceRate' => $attendanceRate,
            'maleEmployees' => $maleEmployees,
            'femaleEmployees' => $femaleEmployees,
            'departmentStats' => $departmentStats,
            'recentLeaves' => $recentLeaves,
        ];

        if ($user->type != 'client' && $user->type != 'company') {
            $emp = Employee::where('user_id', '=', $user->id)->first();

            $announcements = Announcement::where('announcements.end_date', '>=', $currentDate)
                ->orderBy('announcements.id', 'desc')
                ->take(5)
                ->leftjoin('announcement_employees', 'announcements.id', '=', 'announcement_employees.announcement_id')
                ->where('announcement_employees.employee_id', '=', $emp->id)
                ->orWhere(function ($q) use ($emp) {
                    $q->where('announcements.department_id', '["0"]')
                        ->where('announcements.employee_id', '["0"]')
                        ->where('announcement_employees.employee_id', $emp->id);
                })
                ->get();

            $meetings = Meeting::orderBy('meetings.id', 'desc')
                ->take(5)
                ->leftjoin('meeting_employees', 'meetings.id', '=', 'meeting_employees.meeting_id')
                ->where('meeting_employees.employee_id', '=', $emp->id)
                ->orWhere(function ($q) {
                    $q->where('meetings.department_id', '["0"]')->where('meetings.employee_id', '["0"]');
                })
                ->groupBy('meetings.id')
                ->get();

            $events = Event::leftjoin('event_employees', 'events.id', '=', 'event_employees.event_id')
                ->where('event_employees.employee_id', '=', $emp->id)
                ->orWhere(function ($q) {
                    $q->where('events.department_id', '["0"]')->where('events.employee_id', '["0"]');
                })
                ->get();

            $arrEvents = [];
            foreach ($events as $event) {
                $arrEvents[] = [
                    'id' => $event['id'],
                    'title' => $event['title'],
                    'start' => $event['start_date'],
                    'end' => $event['end_date'],
                    'backgroundColor' => $event['color'],
                    'borderColor' => '#fff',
                    'textColor' => 'white',
                ];
            }

            $date = date('Y-m-d');
            $employeeAttendance = AttendanceEmployee::orderBy('id', 'desc')
                ->where('employee_id', '=', !empty(\\Auth::user()->employee) ? \\Auth::user()->employee->id : 0)
                ->where('date', '=', $date)
                ->first();

            $officeTime = [
                'startTime' => Utility::getValByName('company_start_time'),
                'endTime' => Utility::getValByName('company_end_time'),
            ];

            return view('dashboard.dashboard', array_merge($stats, [
                'arrEvents' => $arrEvents,
                'announcements' => $announcements,
                'meetings' => $meetings,
                'employeeAttendance' => $employeeAttendance,
                'officeTime' => $officeTime,
                'isHRMAdmin' => true,
            ]));
        }

        $events = Event::where('created_by', '=', \\Auth::user()->creatorId())->get();
        $arrEvents = [];
        foreach ($events as $event) {
            $arrEvents[] = [
                'id' => $event['id'],
                'title' => $event['title'],
                'start' => $event['start_date'],
                'end' => $event['end_date'],
                'backgroundColor' => $event['color'],
                'borderColor' => '#fff',
                'textColor' => 'white',
                'url' => route('event.edit', $event['id']),
            ];
        }

        $announcements = Announcement::where('end_date', '>=', $currentDate)
            ->orderBy('announcements.id', 'desc')
            ->take(5)
            ->where('created_by', '=', \\Auth::user()->creatorId())
            ->get();

        $meetings = Meeting::where('created_by', '=', \\Auth::user()->creatorId())->limit(5)->get();

        return view('dashboard.dashboard', array_merge($stats, [
            'arrEvents' => $arrEvents,
            'announcements' => $announcements,
            'meetings' => $meetings,
            'employeeAttendance' => null,
            'officeTime' => [],
            'isHRMAdmin' => true,
        ]));
    }
