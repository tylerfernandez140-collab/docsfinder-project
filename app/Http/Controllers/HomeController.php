<?php

namespace App\Http\Controllers;

use App\Models\History;
use App\Models\Upload;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $totalDocuments = $this->totalDocuments();
        $controlledDocuments = $this->controlledDocuments();
        $pendingApprovals = $this->pendingApprovals();
        $totalDownloads = Upload::sum('numdl');

        // Calculate percentage change for total documents
        $previousMonthDocuments = Upload::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        $percentDocuments = $previousMonthDocuments > 0 ? round((($totalDocuments - $previousMonthDocuments) / $previousMonthDocuments) * 100) : 0;

        // Calculate percentage change for pending approvals
        $previousMonthPending = Upload::where('status_upload', 0)->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        $percentPending = $previousMonthPending > 0 ? round((($pendingApprovals - $previousMonthPending) / $previousMonthPending) * 100) : 0;

        // Calculate percentage change for total downloads
        $previousMonthDownloads = Upload::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->sum('numdl');
        $percentDownloads = $previousMonthDownloads > 0 ? round((($totalDownloads - $previousMonthDownloads) / $previousMonthDownloads) * 100) : 0;

        $recentDocuments = Upload::with('user')->latest()->take(5)->get();
        $recentActivities = Activity::with('subject')->latest()->take(5)->get();

        $user = Auth::user();
        $user_role = $user->role ? $user->role->name : 'user';

        // Initialize all variables to avoid 'Undefined variable' errors
        $totalDocumentsTitle = "";
        $controlledDocumentsTitle = "";
        $pendingApprovalsTitle = "";
        $recentDocumentsTitle = "";
        $totalDocumentsSubtitle = "";
        $controlledDocumentsSubtitle = "";
        $pendingApprovalsSubtitle = "";
        $recentDocumentsSubtitle = "";
        $totalDocumentsRoute = route('documents.index');
        $controlledDocumentsRoute = route('documents.controlled');
        $pendingApprovalsRoute = route('documents.pending');
        $totalDownloadsRoute = route('documents.downloads');

        // Admin specific stats
        $totalUploads = 0;
        $uploadsThisMonthCount = 0;
        $pendingReviewByCampusDCC = 0;
        $totalUploadsTitle = "";
        $uploadsThisMonthTitle = "";
        $pendingReviewByCampusDCCTitle = "";
        $totalUploadsSubtitle = "";
        $uploadsThisMonthSubtitle = "";
        $pendingReviewByCampusDCCSubtitle = "";
        $totalUploadsRoute = '#';
        $uploadsThisMonthRoute = '#';
        $pendingReviewByCampusDCCRoute = '#';

        // Campus DCC specific stats
        $totalDocumentsDistributed = 0;
        $pendingDistributions = 0;
        $distributedThisMonthCount = 0;
        $totalDocumentsDistributedTitle = "";
        $pendingDistributionsTitle = "";
        $distributedThisMonthTitle = "";
        $totalDocumentsDistributedSubtitle = "";
        $pendingDistributionsSubtitle = "";
        $distributedThisMonthSubtitle = "";
        $totalDocumentsDistributedRoute = '#';
        $pendingDistributionsRoute = '#';
        $distributedThisMonthRoute = '#';

        // Process Owner specific stats
        $documentsAssignedToMe = 0;
        $pendingFeedbackRequests = 0;
        $feedbackCommentHistory = 0;
        $documentsAssignedToMeTitle = "";
        $pendingFeedbackRequestsTitle = "";
        $feedbackCommentHistoryTitle = "";
        $documentsAssignedToMeSubtitle = "";
        $pendingFeedbackRequestsSubtitle = "";
        $feedbackCommentHistorySubtitle = "";
        $documentsAssignedToMeRoute = '#';
        $pendingFeedbackRequestsRoute = '#';
        $feedbackCommentHistoryRoute = '#';

        if ($user_role == 'super-admin') {
            $totalDocumentsTitle = 'Total Documents';
            $controlledDocumentsTitle = 'Controlled Documents';
            $pendingApprovalsTitle = 'Pending Approvals';
            $totalDownloadsTitle = 'Total Downloads';
            $totalDocumentsSubtitle = '+' . $percentDocuments . '% from last month';
            $controlledDocumentsSubtitle = 'Documents marked as controlled';
            $pendingApprovalsSubtitle = '+' . $percentPending . '% from last month';
            $totalDownloadsSubtitle = '+' . $percentDownloads . '% from last month';
            $totalDocumentsRoute = route('documents.index');
            $controlledDocumentsRoute = route('documents.controlled');
            $pendingApprovalsRoute = route('documents.pending');
            $totalDownloadsRoute = route('documents.downloads');
            $recentDocuments = Upload::with('user')->latest()->take(5)->get();
            $recentDocumentsTitle = 'Recent Documents';
            $recentDocumentsSubtitle = 'Latest documents in the system';
        } elseif ($user_role == 'admin') {
            $totalUploads = Upload::where('user_id', $user->id)->count();
            $uploadsThisMonthCount = Upload::where('user_id', $user->id)->whereMonth('created_at', Carbon::now()->month)->count();
            $pendingReviewByCampusDCC = Upload::where('user_id', $user->id)->where('status_upload', 0)->count();
            $totalUploadsTitle = 'Total Uploads';
            $uploadsThisMonthTitle = 'Uploads This Month';
            $pendingReviewByCampusDCCTitle = 'Pending Review by Campus DCC';
            $totalUploadsSubtitle = $uploadsThisMonthCount > 0 ? '+' . round((($totalUploads - $uploadsThisMonthCount) / $uploadsThisMonthCount) * 100) . '% from last month' : '0% from last month';
            $uploadsThisMonthSubtitle = 'Documents uploaded this month';
            $pendingReviewByCampusDCCSubtitle = 'Documents pending review';
            $totalUploadsRoute = route('documents.index');
            $uploadsThisMonthRoute = route('documents.index'); // Assuming a route for uploads this month
            $pendingReviewByCampusDCCRoute = route('documents.pending');
            $recentDocuments = Upload::where('user_id', $user->id)->latest()->take(5)->get();
            $recentDocumentsTitle = 'My Recent Uploads';
            $recentDocumentsSubtitle = 'Your latest uploaded documents';
        } elseif ($user_role == 'campus-dcc') {
            $totalDocumentsDistributed = Upload::where('distributed_to_designation', $user->designation_id)->count();
            $pendingDistributions = Upload::where('distributed_to_designation', $user->designation_id)->where('status_distribution', 0)->count();
            $distributedThisMonthCount = Upload::where('distributed_to_designation', $user->designation_id)->whereMonth('distributed_at', Carbon::now()->month)->count();
            $totalDocumentsDistributedTitle = 'Total Documents Distributed';
            $pendingDistributionsTitle = 'Pending Distributions';
            $distributedThisMonthTitle = 'Distributed This Month';
            $totalDocumentsDistributedSubtitle = $distributedThisMonthCount > 0 ? '+' . round((($totalDocumentsDistributed - $distributedThisMonthCount) / $distributedThisMonthCount) * 100) . '% from last month' : '0% from last month';
            $pendingDistributionsSubtitle = 'Documents awaiting distribution';
            $distributedThisMonthSubtitle = 'Documents distributed this month';
            $totalDocumentsDistributedRoute = route('documents.distributed');
            $pendingDistributionsRoute = route('documents.pending-distribution');
            $distributedThisMonthRoute = route('documents.distributed-this-month');
            $recentDocuments = Upload::where('distributed_to_designation', $user->designation_id)->latest()->take(5)->get();
            $recentDocumentsTitle = 'Recently Distributed Documents';
            $recentDocumentsSubtitle = 'Latest documents distributed by you';
        } elseif ($user_role == 'process-owner') {
            $documentsAssignedToMe = Upload::where('process_owner_id', $user->id)->count();
            $pendingFeedbackRequests = Upload::where('process_owner_id', $user->id)->where('status_feedback', 0)->count();
            $feedbackCommentHistory = Upload::where('process_owner_id', $user->id)->where('status_feedback', 1)->count();
            $documentsAssignedToMeTitle = 'Documents Assigned to Me';
            $pendingFeedbackRequestsTitle = 'Pending Feedback Requests';
            $feedbackCommentHistoryTitle = 'Feedback/Comments History';
            $documentsAssignedToMeSubtitle = 'Documents assigned by Campus DCC';
            $pendingFeedbackRequestsSubtitle = 'Documents waiting for your feedback';
            $feedbackCommentHistorySubtitle = 'Your submitted feedback history';
            $documentsAssignedToMeRoute = route('documents.assigned');
            $pendingFeedbackRequestsRoute = route('documents.pending-feedback');
            $feedbackCommentHistoryRoute = route('documents.feedback-history');
            $recentDocuments = Upload::where('process_owner_id', $user->id)->latest()->take(5)->get();
            $recentDocumentsTitle = 'Recently Viewed Documents';
            $recentDocumentsSubtitle = 'Documents you viewed recently';
        } else {
            // Default for regular users or if no specific role is matched
            $totalDocumentsTitle = 'Total Documents';
            $controlledDocumentsTitle = 'Controlled Documents';
            $pendingApprovalsTitle = 'Pending Approvals';
            $totalDownloadsTitle = 'Total Downloads';
            $totalDocumentsSubtitle = '+' . $percentDocuments . '% from last month';
            $controlledDocumentsSubtitle = 'Documents marked as controlled';
            $pendingApprovalsSubtitle = '+' . $percentPending . '% from last month';
            $totalDownloadsSubtitle = '+' . $percentDownloads . '% from last month';
            $totalDocumentsRoute = route('documents.index');
            $controlledDocumentsRoute = route('documents.controlled');
            $pendingApprovalsRoute = route('documents.pending');
            $totalDownloadsRoute = route('documents.downloads');
            $recentDocuments = Upload::with('user')->latest()->take(5)->get();
            $recentDocumentsTitle = 'Recent Documents';
            $recentDocumentsSubtitle = 'Latest document activities and updates';
        }

        return view('home', compact(
            'totalDocuments',
            'controlledDocuments',
            'pendingApprovals',
            'totalDownloads',
            'percentDocuments',
            'percentPending',
            'percentDownloads',
            'recentDocuments',
            'recentActivities',
            'user_role',
            'totalDocumentsTitle',
            'controlledDocumentsTitle',
            'pendingApprovalsTitle',
            'recentDocumentsTitle',
            'totalDocumentsSubtitle',
            'controlledDocumentsSubtitle',
            'pendingApprovalsSubtitle',
            'recentDocumentsSubtitle',
            'totalDocumentsRoute',
            'controlledDocumentsRoute',
            'pendingApprovalsRoute',
            'totalDownloadsRoute',
            // Admin specific
            'totalUploads',
            'uploadsThisMonthCount',
            'pendingReviewByCampusDCC',
            'totalUploadsTitle',
            'uploadsThisMonthTitle',
            'pendingReviewByCampusDCCTitle',
            'totalUploadsSubtitle',
            'uploadsThisMonthSubtitle',
            'pendingReviewByCampusDCCSubtitle',
            'totalUploadsRoute',
            'uploadsThisMonthRoute',
            'pendingReviewByCampusDCCRoute',
            // Campus DCC specific
            'totalDocumentsDistributed',
            'pendingDistributions',
            'distributedThisMonthCount',
            'totalDocumentsDistributedTitle',
            'pendingDistributionsTitle',
            'distributedThisMonthTitle',
            'totalDocumentsDistributedSubtitle',
            'pendingDistributionsSubtitle',
            'distributedThisMonthSubtitle',
            'totalDocumentsDistributedRoute',
            'pendingDistributionsRoute',
            'distributedThisMonthRoute',
            // Process Owner specific
            'documentsAssignedToMe',
            'pendingFeedbackRequests',
            'feedbackCommentHistory',
            'documentsAssignedToMeTitle',
            'pendingFeedbackRequestsTitle',
            'feedbackCommentHistoryTitle',
            'documentsAssignedToMeSubtitle',
            'pendingFeedbackRequestsSubtitle',
            'feedbackCommentHistorySubtitle',
            'documentsAssignedToMeRoute',
            'pendingFeedbackRequestsRoute',
            'feedbackCommentHistoryRoute'
        ));
    }

    public function totalDocuments() {
        return Upload::count();
    }

    public function documentsIndex()
    {
        $totalDocuments = $this->totalDocuments();
        $totalDocumentsTitle = 'Total Documents';
        $totalDocumentsSubtitle = 'Overview of all documents';
        return view('documents.index', compact('totalDocuments', 'totalDocumentsTitle', 'totalDocumentsSubtitle'));
    }

    public function documentsControlled()
    {
        $controlledDocumentsList = $this->controlledDocumentsList();
        return view('documents.controlled', compact('controlledDocumentsList'));
    }

    protected function controlledDocumentsList()
    {
        return Upload::where('status_upload', 1)->get();
    }

    public function documentsPending()
    {
        $pendingApprovalsList = $this->pendingApprovalsList();
        return view('documents.pending', compact('pendingApprovalsList'));
    }

    protected function pendingApprovalsList()
    {
        return Upload::where('status_upload', 0)->get();
    }

    public function feedbackPending()
    {
        return view('feedback.pending');
    }

    public function feedbackHistory()
    {
        return view('feedback.history');
    }

    public function documentsAssigned()
    {
        return view('documents.assigned');
    }

    public function documentsDistributedThisMonth()
    {
        return view('documents.distributed-this-month');
    }

    public function documentsPendingDistribution()
    {
        return view('documents.pending-distribution');
    }

    public function documentsDownloads()
    {
        $allDownloads = $this->allDownloads();
        return view('documents.downloads', compact('allDownloads'));
    }

    protected function allDownloads()
    {
        return Upload::all();
    }

    public function performDistribution()
    {
        //
    }

    public function distribute()
    {
        return view('documents.distribute');
    }

    public function distributed()
    {
        return view('documents.distributed');
    }

    public function myDocuments()
    {
        return view('documents.my');
    }

    public function feedback()
    {
        return view('documents.feedback');
    }

    public function apiDocumentsIndex()
    {
        $documents = Upload::all();
        return response()->json(['data' => $documents]);
    }
}
