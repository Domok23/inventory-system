@extends('layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="card shadow rounded">
            <div class="card-body">
                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-2 mb-3">
                    <!-- Header -->
                    <div class="d-flex align-items-center mb-2 mb-lg-0">
                        <i class="fas fa-bug gradient-icon me-2" style="font-size: 1.5rem;"></i>
                        <h2 class="mb-0 flex-shrink-0" style="font-size:1.3rem;">Error Pages Demo</h2>
                    </div>
                </div>

                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Demo Mode:</strong> Click on any error code below to test the custom error pages.
                    These pages are displayed when the application encounters HTTP status code errors.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>

                <!-- Tabs for Client, Server, and Redirect Errors -->
                <ul class="nav nav-tabs mb-4" id="errorTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="client-tab" data-bs-toggle="tab" data-bs-target="#client-errors"
                            type="button" role="tab" aria-controls="client-errors" aria-selected="true">
                            <i class="fas fa-user-times me-2"></i>4xx Client Errors
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="server-tab" data-bs-toggle="tab" data-bs-target="#server-errors"
                            type="button" role="tab" aria-controls="server-errors" aria-selected="false">
                            <i class="fas fa-server me-2"></i>5xx Server Errors
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="redirect-tab" data-bs-toggle="tab" data-bs-target="#redirect-errors"
                            type="button" role="tab" aria-controls="redirect-errors" aria-selected="false">
                            <i class="fas fa-directions me-2"></i>3xx Redirect Errors
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="errorTabsContent">
                    <div class="tab-pane fade show active" id="client-errors" role="tabpanel" aria-labelledby="client-tab">

                        <div class="row g-3">
                            <!-- 400 Bad Request -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-danger h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #dc3545;">400</div>
                                        <h6 class="card-title">Bad Request</h6>
                                        <p class="card-text small text-muted">Invalid syntax in request</p>
                                        <a href="{{ route('demo.400') }}" class="btn btn-outline-danger btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 401 Unauthorized -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-warning h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #ffc107;">401</div>
                                        <h6 class="card-title">Unauthorized</h6>
                                        <p class="card-text small text-muted">Authentication required</p>
                                        <a href="{{ route('demo.401') }}" class="btn btn-outline-warning btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 403 Forbidden -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-danger h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #dc3545;">403</div>
                                        <h6 class="card-title">Forbidden</h6>
                                        <p class="card-text small text-muted">Access denied</p>
                                        <a href="{{ route('demo.403') }}" class="btn btn-outline-danger btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 404 Not Found -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-primary h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #0d6efd;">404</div>
                                        <h6 class="card-title">Not Found</h6>
                                        <p class="card-text small text-muted">Page not found</p>
                                        <a href="{{ route('demo.404') }}" class="btn btn-outline-primary btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 405 Method Not Allowed -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-secondary h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #6c757d;">405</div>
                                        <h6 class="card-title">Method Not Allowed</h6>
                                        <p class="card-text small text-muted">HTTP method not supported</p>
                                        <a href="{{ route('demo.405') }}" class="btn btn-outline-secondary btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 406 Not Acceptable -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #0dcaf0;">406</div>
                                        <h6 class="card-title">Not Acceptable</h6>
                                        <p class="card-text small text-muted">Cannot produce acceptable response</p>
                                        <a href="{{ route('demo.406') }}" class="btn btn-outline-info btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 408 Request Timeout -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-warning h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #ffc107;">408</div>
                                        <h6 class="card-title">Request Timeout</h6>
                                        <p class="card-text small text-muted">Server timed out</p>
                                        <a href="{{ route('demo.408') }}" class="btn btn-outline-warning btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 409 Conflict -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-danger h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #dc3545;">409</div>
                                        <h6 class="card-title">Conflict</h6>
                                        <p class="card-text small text-muted">Request conflicts with current state</p>
                                        <a href="{{ route('demo.409') }}" class="btn btn-outline-danger btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 410 Gone -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-dark h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #212529;">410</div>
                                        <h6 class="card-title">Gone</h6>
                                        <p class="card-text small text-muted">Resource no longer available</p>
                                        <a href="{{ route('demo.410') }}" class="btn btn-outline-dark btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 411 Length Required -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-secondary h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #6c757d;">411</div>
                                        <h6 class="card-title">Length Required</h6>
                                        <p class="card-text small text-muted">Content length not specified</p>
                                        <a href="{{ route('demo.411') }}" class="btn btn-outline-secondary btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 413 Payload Too Large -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-warning h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #ffc107;">413</div>
                                        <h6 class="card-title">Payload Too Large</h6>
                                        <p class="card-text small text-muted">Request entity too large</p>
                                        <a href="{{ route('demo.413') }}" class="btn btn-outline-warning btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 414 URI Too Long -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #0dcaf0;">414</div>
                                        <h6 class="card-title">URI Too Long</h6>
                                        <p class="card-text small text-muted">URI provided was too long</p>
                                        <a href="{{ route('demo.414') }}" class="btn btn-outline-info btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 415 Unsupported Media Type -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-danger h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #dc3545;">415</div>
                                        <h6 class="card-title">Unsupported Media Type</h6>
                                        <p class="card-text small text-muted">Media format not supported</p>
                                        <a href="{{ route('demo.415') }}" class="btn btn-outline-danger btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 422 Unprocessable Entity -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-warning h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #ffc107;">422</div>
                                        <h6 class="card-title">Unprocessable Entity</h6>
                                        <p class="card-text small text-muted">Semantic errors in request</p>
                                        <a href="{{ route('demo.422') }}" class="btn btn-outline-warning btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="tab-pane fade" id="server-errors" role="tabpanel" aria-labelledby="server-tab">
                        <div class="row g-3">
                            <!-- 500 Internal Server Error -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-danger h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #dc3545;">500</div>
                                        <h6 class="card-title">Internal Server Error</h6>
                                        <p class="card-text small text-muted">Server encountered an error</p>
                                        <a href="{{ route('demo.500') }}" class="btn btn-outline-danger btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 501 Not Implemented -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-warning h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #ffc107;">501</div>
                                        <h6 class="card-title">Not Implemented</h6>
                                        <p class="card-text small text-muted">Functionality not implemented</p>
                                        <a href="{{ route('demo.501') }}" class="btn btn-outline-warning btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 502 Bad Gateway -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-danger h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #dc3545;">502</div>
                                        <h6 class="card-title">Bad Gateway</h6>
                                        <p class="card-text small text-muted">Invalid response from upstream</p>
                                        <a href="{{ route('demo.502') }}" class="btn btn-outline-danger btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 503 Service Unavailable -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-primary h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #0d6efd;">503</div>
                                        <h6 class="card-title">Service Unavailable</h6>
                                        <p class="card-text small text-muted">Temporary maintenance</p>
                                        <a href="{{ route('demo.503') }}" class="btn btn-outline-primary btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 504 Gateway Timeout -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-secondary h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #6c757d;">504</div>
                                        <h6 class="card-title">Gateway Timeout</h6>
                                        <p class="card-text small text-muted">Server took too long to respond</p>
                                        <a href="{{ route('demo.504') }}" class="btn btn-outline-secondary btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 505 HTTP Version Not Supported -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #0dcaf0;">505</div>
                                        <h6 class="card-title">HTTP Version Not Supported</h6>
                                        <p class="card-text small text-muted">HTTP version not supported</p>
                                        <a href="{{ route('demo.505') }}" class="btn btn-outline-info btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 507 Insufficient Storage -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-warning h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #ffc107;">507</div>
                                        <h6 class="card-title">Insufficient Storage</h6>
                                        <p class="card-text small text-muted">Server storage full</p>
                                        <a href="{{ route('demo.507') }}" class="btn btn-outline-warning btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 508 Loop Detected -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-danger h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #dc3545;">508</div>
                                        <h6 class="card-title">Loop Detected</h6>
                                        <p class="card-text small text-muted">Infinite loop detected</p>
                                        <a href="{{ route('demo.508') }}" class="btn btn-outline-danger btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 511 Network Authentication Required -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-dark h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #212529;">511</div>
                                        <h6 class="card-title">Network Auth Required</h6>
                                        <p class="card-text small text-muted">Network authentication needed</p>
                                        <a href="{{ route('demo.511') }}" class="btn btn-outline-dark btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="redirect-errors" role="tabpanel" aria-labelledby="redirect-tab">
                        <div class="row g-3">
                            <!-- 300 Multiple Choices -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-success h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #28a745;">300</div>
                                        <h6 class="card-title">Multiple Choices</h6>
                                        <p class="card-text small text-muted">Multiple options available</p>
                                        <a href="{{ route('demo.300') }}" class="btn btn-outline-success btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 301 Moved Permanently -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-primary h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #0d6efd;">301</div>
                                        <h6 class="card-title">Moved Permanently</h6>
                                        <p class="card-text small text-muted">Permanent redirect</p>
                                        <a href="{{ route('demo.301') }}" class="btn btn-outline-primary btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 302 Found -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #0dcaf0;">302</div>
                                        <h6 class="card-title">Found</h6>
                                        <p class="card-text small text-muted">Temporary redirect</p>
                                        <a href="{{ route('demo.302') }}" class="btn btn-outline-info btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 303 See Other -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-warning h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #ffc107;">303</div>
                                        <h6 class="card-title">See Other</h6>
                                        <p class="card-text small text-muted">See other location</p>
                                        <a href="{{ route('demo.303') }}" class="btn btn-outline-warning btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 304 Not Modified -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-secondary h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #6c757d;">304</div>
                                        <h6 class="card-title">Not Modified</h6>
                                        <p class="card-text small text-muted">Content not modified</p>
                                        <a href="{{ route('demo.304') }}" class="btn btn-outline-secondary btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 305 Use Proxy -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-dark h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #212529;">305</div>
                                        <h6 class="card-title">Use Proxy</h6>
                                        <p class="card-text small text-muted">Proxy required</p>
                                        <a href="{{ route('demo.305') }}" class="btn btn-outline-dark btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 306 Switch Proxy (Deprecated) -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-danger h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #dc3545;">306</div>
                                        <h6 class="card-title">Switch Proxy</h6>
                                        <p class="card-text small text-muted">Deprecated status</p>
                                        <a href="{{ route('demo.306') }}" class="btn btn-outline-danger btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 307 Temporary Redirect -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-info h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #0dcaf0;">307</div>
                                        <h6 class="card-title">Temporary Redirect</h6>
                                        <p class="card-text small text-muted">Preserve request method</p>
                                        <a href="{{ route('demo.307') }}" class="btn btn-outline-info btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 308 Permanent Redirect -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-success h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #28a745;">308</div>
                                        <h6 class="card-title">Permanent Redirect</h6>
                                        <p class="card-text small text-muted">Preserve request method</p>
                                        <a href="{{ route('demo.308') }}" class="btn btn-outline-success btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- 310 Multiple Representations -->
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-primary h-100">
                                    <div class="card-body text-center">
                                        <div class="error-code-demo mb-2"
                                            style="font-size: 2rem; font-weight: bold; color: #0d6efd;">310</div>
                                        <h6 class="card-title">Multiple Representations</h6>
                                        <p class="card-text small text-muted">Experimental status</p>
                                        <a href="{{ route('demo.310') }}" class="btn btn-outline-primary btn-sm"
                                            target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Test
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="alert alert-light border">
                        <h6><i class="fas fa-lightbulb text-warning me-2"></i>Tips:</h6>
                        <ul class="mb-0 small">
                            <li>Error pages will open in a new tab to preserve your current session</li>
                            <li>Each error page has a consistent design with the application theme</li>
                            <li>Users can easily navigate back or return to home from error pages</li>
                            <li>The design is responsive and works on all devices</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .gradient-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card {
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
        }
    </style>
@endpush
