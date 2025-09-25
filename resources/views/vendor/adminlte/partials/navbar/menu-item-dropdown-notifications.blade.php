@php
$unreadCount = Auth::check() ? 
    App\Models\Notification::where('user_id', Auth::id())
        ->where('read', false)
        ->count() : 0;
@endphp

<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
        <i class="far fa-bell"></i>
        @if($unreadCount > 0)
            <span class="badge badge-danger navbar-badge">{{ $unreadCount }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
        <span class="dropdown-item dropdown-header">🔔 Notifications ({{ $unreadCount }})</span>
        <div class="dropdown-divider"></div>
        
        @if(Auth::check())
            @php
                $notifications = App\Models\Notification::where('user_id', Auth::id())
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();
            @endphp
            
            @forelse($notifications as $notification)
                    @php
                        $iconClass = 'fas fa-bell';
                        $iconColor = 'text-warning';
                        $displayMessage = Str::limit($notification->message, 40);

                        switch ($notification->type) {
                            case 'upload_created':
                                $iconClass = 'fas fa-upload';
                                $iconColor = 'text-success';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Document \"' . ($notificationData['upload_filename'] ?? 'N/A') . '\" uploaded';
                                break;
                            case 'upload_updated':
                                $iconClass = 'fas fa-edit';
                                $iconColor = 'text-info';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Document \"' . ($notificationData['upload_filename'] ?? 'N/A') . '\" updated';
                                break;
                            case 'edit_request':
                                $iconClass = 'fas fa-pencil-alt';
                                $iconColor = 'text-primary';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Edit requested for document \"' . ($notificationData['upload_filename'] ?? 'N/A') . '\"';
                                break;
                            case 'download':
                                $iconClass = 'fas fa-download';
                                $iconColor = 'text-secondary';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Document \"' . ($notificationData['upload_filename'] ?? 'N/A') . '\" downloaded';
                                break;
                            case 'upload_deleted':
                                $iconClass = 'fas fa-trash-alt';
                                $iconColor = 'text-danger';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Document \"' . ($notificationData['upload_filename'] ?? 'N/A') . '\" deleted';
                                break;
                            case 'document_uploaded':
                                $iconClass = 'fas fa-upload';
                                $iconColor = 'text-success';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Document "' . ($notificationData['title'] ?? 'N/A') . '" (Control No: ' . ($notificationData['control_number'] ?? 'N/A') . ') uploaded by ' . ($notificationData['by'] ?? 'N/A');
                                break;
                            case 'document_revised':
                                $iconClass = 'fas fa-edit';
                                $iconColor = 'text-info';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Document "' . ($notificationData['title'] ?? 'N/A') . '" (Control No: ' . ($notificationData['control_number'] ?? 'N/A') . ', Version: ' . ($notificationData['version'] ?? 'N/A') . ') revised by ' . ($notificationData['by'] ?? 'N/A');
                                break;
                            case 'document_approved':
                                $iconClass = 'fas fa-check-circle';
                                $iconColor = 'text-success';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Document "' . ($notificationData['title'] ?? 'N/A') . '" (Control No: ' . ($notificationData['control_number'] ?? 'N/A') . ') approved by ' . ($notificationData['by'] ?? 'N/A');
                                break;
                            case 'document_rejected':
                                $iconClass = 'fas fa-times-circle';
                                $iconColor = 'text-danger';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Document "' . ($notificationData['title'] ?? 'N/A') . '" (Control No: ' . ($notificationData['control_number'] ?? 'N/A') . ') rejected by ' . ($notificationData['by'] ?? 'N/A');
                                break;
                            case 'document_archived':
                                $iconClass = 'fas fa-archive';
                                $iconColor = 'text-secondary';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Document "' . ($notificationData['title'] ?? 'N/A') . '" (Control No: ' . ($notificationData['control_number'] ?? 'N/A') . ') archived by ' . ($notificationData['by'] ?? 'N/A');
                                break;
                            case 'document_unarchived':
                                $iconClass = 'fas fa-box-open';
                                $iconColor = 'text-primary';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Document "' . ($notificationData['title'] ?? 'N/A') . '" (Control No: ' . ($notificationData['control_number'] ?? 'N/A') . ') unarchived by ' . ($notificationData['by'] ?? 'N/A');
                                break;
                            case 'document_deleted':
                                $iconClass = 'fas fa-trash-alt';
                                $iconColor = 'text-danger';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Document "' . ($notificationData['title'] ?? 'N/A') . '" (Control No: ' . ($notificationData['control_number'] ?? 'N/A') . ') deleted by ' . ($notificationData['by'] ?? 'N/A');
                                break;
                            case 'document_edited':
                                $iconClass = 'fas fa-file-signature';
                                $iconColor = 'text-dark';
                                $notificationData = json_decode($notification->data, true);
                                $displayMessage = 'Document \"' . ($notificationData['upload_filename'] ?? 'N/A') . '\" directly edited by Super Admin';
                                break;
                            case 'approval':
                                $iconClass = 'fas fa-check-circle';
                                $iconColor = 'text-success';
                                break;
                            case 'rejection':
                                $iconClass = 'fas fa-times-circle';
                                $iconColor = 'text-danger';
                                break;
                            default:
                                // Use default bell icon and message
                                break;
                        }
                    @endphp
                    <a href="{{ route('notifications.mark-read', $notification->id) }}" class="dropdown-item {{ $notification->read ? '' : 'bg-light' }}">
                        <i class="{{ $iconClass }} mr-2 {{ $iconColor }}"></i>
                        <div class="media">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                <span class="{{ $notification->read ? '' : 'font-weight-bold' }}">{{ $displayMessage }}</span>
                            </h3>
                            @if(isset($notificationData['by']))
                                <p class="text-sm">By: {{ $notificationData['by'] }}</p>
                            @endif
                            @if(isset($notificationData['status']))
                                <p class="text-sm">Status: {{ $notificationData['status'] }}</p>
                            @endif
                            <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> {{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
            @empty
                <span class="dropdown-item text-muted">No notifications</span>
                <div class="dropdown-divider"></div>
            @endforelse
        @endif
        
        <a href="{{ route('notifications.index') }}" class="dropdown-item dropdown-footer">See All Notifications</a>
    </div>
</li>