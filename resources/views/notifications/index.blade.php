@extends('adminlte::page')

@section('title', 'Notifications')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Notifications</h1>
        @if($notifications->where('read', false)->count() > 0)
            <a href="{{ route('notifications.mark-all-read') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-check-double"></i> Mark All as Read
            </a>
        @endif
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($notifications->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">You don't have any notifications yet.</p>
                </div>
            @else
                <ul class="list-group notification-list">
                    @foreach($notifications as $notification)
                        <li class="list-group-item d-flex justify-content-between align-items-center {{ $notification->read ? 'bg-light' : '' }}">
                            <div class="notification-content">
                                <div class="d-flex align-items-center">
                                    @if($notification->type == 'request')
                                        <i class="fas fa-file-alt text-primary mr-3"></i>
                                    @elseif($notification->type == 'approval')
                                        <i class="fas fa-check-circle text-success mr-3"></i>
                                    @elseif($notification->type == 'rejection')
                                        <i class="fas fa-times-circle text-danger mr-3"></i>
                                    @else
                                        <i class="fas fa-bell text-warning mr-3"></i>
                                    @endif
                                    
                                    <div>
                                        <p class="mb-1 {{ $notification->read ? 'text-muted' : 'font-weight-bold' }}">
                                            @php
                                                $notificationData = json_decode($notification->data, true);
                                                $message = $notification->message;
                                                if (isset($notificationData['upload_filename'])) {
                                                    switch ($notification->type) {
                                                        case 'document_uploaded':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" (Control No: ' . $notificationData['control_number'] . ') has been uploaded.';
                                                            break;
                                                        case 'document_revised':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" (Control No: ' . $notificationData['control_number'] . ') has been revised to version ' . $notificationData['version'] . '.';
                                                            break;
                                                        case 'document_metadata_updated':
                                                            $message = 'Metadata for document "' . $notificationData['upload_filename'] . '" (Control No: ' . $notificationData['control_number'] . ') has been updated.';
                                                            break;
                                                        case 'document_approved':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" (Control No: ' . $notificationData['control_number'] . ') has been approved.';
                                                            break;
                                                        case 'document_rejected':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" (Control No: ' . $notificationData['control_number'] . ') has been rejected.';
                                                            break;
                                                        case 'document_archived':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" (Control No: ' . $notificationData['control_number'] . ') has been archived.';
                                                            break;
                                                        case 'document_unarchived':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" (Control No: ' . $notificationData['control_number'] . ') has been unarchived.';
                                                            break;
                                                        case 'document_deleted':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" (Control No: ' . $notificationData['control_number'] . ') has been deleted.';
                                                            break;
                                                        case 'upload_created':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" uploaded';
                                                            break;
                                                        case 'upload_updated':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" updated';
                                                            break;
                                                        case 'edit_request':
                                                            $message = 'Edit requested for document "' . $notificationData['upload_filename'] . '"';
                                                            break;
                                                        case 'download':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" downloaded';
                                                            break;
                                                        case 'upload_deleted':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" deleted';
                                                            break;
                                                        case 'metadata_updated':
                                                            $message = 'Metadata updated for document "' . $notificationData['upload_filename'] . '"';
                                                            break;
                                                        case 'document_controlled':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" moved to Controlled';
                                                            break;
                                                        case 'document_uncontrolled':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" moved to Uncontrolled';
                                                            break;
                                                        case 'revision_approved':
                                                            $message = 'Revision request for document "' . $notificationData['upload_filename'] . '" approved';
                                                            break;
                                                        case 'revision_rejected':
                                                            $message = 'Revision request for document "' . $notificationData['upload_filename'] . '" rejected';
                                                            break;
                                                        case 'document_edited':
                                                            $message = 'Document "' . $notificationData['upload_filename'] . '" directly edited by Super Admin';
                                                            break;
                                                    }
                                                }
                                            @endphp
                                            {{ $message }}
                                         </p>
                                         @php
                                             $notificationData = json_decode($notification->data, true);
                                         @endphp
                                         @if(isset($notificationData['by']))
                                            <p class="mb-0 text-muted">By: {{ $notificationData['by'] }}</p>
                                        @endif
                                        @if(isset($notificationData['status']))
                                            <p class="mb-0 text-muted">Status: {{ $notificationData['status'] }}</p>
                                        @endif
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="notification-actions">
                                @if(!$notification->read)
                                    <a href="{{ route('notifications.mark-read', $notification->id) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-check"></i> Mark as Read
                                    </a>
                                @endif
                                
                                @if($notification->link)
                                    <a href="{{ $notification->link }}" class="btn btn-sm btn-primary ml-2">
                                        <i class="fas fa-external-link-alt"></i> View
                                    </a>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
                
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <style>
        .notification-list .list-group-item {
            border-left: 3px solid transparent;
            transition: all 0.2s;
        }
        
        .notification-list .list-group-item:not(.bg-light) {
            border-left-color: #007bff;
        }
        
        .notification-content {
            flex: 1;
        }
        
        @media (max-width: 768px) {
            .notification-actions {
                margin-top: 10px;
            }
            
            .list-group-item {
                flex-direction: column;
                align-items: flex-start !important;
            }
        }
    </style>
@stop