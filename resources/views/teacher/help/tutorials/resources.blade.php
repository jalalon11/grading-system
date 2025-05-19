@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Learning Resource Materials Tutorial</h5>
                    <a href="{{ route('teacher.help.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Help Center
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4>Using Learning Resource Materials</h4>
                            <p class="text-muted">
                                The Learning Resource Materials module provides access to educational resources and teaching materials organized by categories and quarters.
                            </p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            Understanding Learning Resource Materials
                        </h5>
                        <div class="tutorial-content">
                            <p>Learning Resource Materials are digital educational resources that help you enhance your teaching. These resources are:</p>
                            <ul>
                                <li>Organized by categories (e.g., Lesson Plans, Worksheets, Visual Aids)</li>
                                <li>Grouped by quarters to align with your curriculum timeline</li>
                                <li>Curated by administrators to ensure quality and relevance</li>
                                <li>Regularly updated with new materials</li>
                            </ul>
                            <p>These resources can include:</p>
                            <ul>
                                <li>Downloadable lesson plans and teaching guides</li>
                                <li>Interactive worksheets and activities</li>
                                <li>Presentation materials and visual aids</li>
                                <li>Assessment tools and rubrics</li>
                                <li>Reference materials and educational videos</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-desktop text-success me-2"></i>
                            Accessing Learning Resource Materials
                        </h5>
                        <div class="tutorial-content">
                            <p>There are two ways to access Learning Resource Materials:</p>
                            <ol>
                                <li><strong>From the Dashboard:</strong>
                                    <ul>
                                        <li>Look for the "Learning Resource Materials" card on your dashboard</li>
                                        <li>Click on a category to view resources in that category</li>
                                        <li>Click "View All Resources" to see the complete resource library</li>
                                    </ul>
                                </li>
                                <li><strong>From the Navigation Menu:</strong>
                                    <ul>
                                        <li>Click on "Resources" in the main navigation menu</li>
                                        <li>This takes you directly to the complete resource library</li>
                                    </ul>
                                </li>
                            </ol>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>The dashboard shows a quick overview of available resource categories, while the Resources page provides more detailed filtering and search options.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-search text-info me-2"></i>
                            Searching and Filtering Resources
                        </h5>
                        <div class="tutorial-content">
                            <p>To find specific resources:</p>
                            <ol>
                                <li><strong>Search by Keyword:</strong>
                                    <ul>
                                        <li>Use the search bar at the top of the Resources page</li>
                                        <li>Enter keywords related to the resource you're looking for</li>
                                        <li>Results will update automatically as you type</li>
                                    </ul>
                                </li>
                                <li><strong>Filter by Quarter:</strong>
                                    <ul>
                                        <li>Use the Quarter dropdown to select a specific quarter (1st, 2nd, 3rd, or 4th)</li>
                                        <li>Select "All Quarters" to view resources for the entire school year</li>
                                    </ul>
                                </li>
                                <li><strong>Filter by Category:</strong>
                                    <ul>
                                        <li>Use the Category dropdown to select a specific resource category</li>
                                        <li>Click on category pills (quick filters) below the search bar</li>
                                    </ul>
                                </li>
                            </ol>
                            <p>You can combine these filters to narrow down your search. For example, you can search for "fractions" in the "Worksheets" category for the "2nd Quarter".</p>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-external-link-alt text-warning me-2"></i>
                            Using Resource Materials
                        </h5>
                        <div class="tutorial-content">
                            <p>To use a resource:</p>
                            <ol>
                                <li>Browse or search for resources in the resource library</li>
                                <li>Hover over a resource card to see the resource overlay</li>
                                <li>Click the "Open Resource" button to access the resource</li>
                                <li>The resource will open in a new browser tab</li>
                            </ol>
                            <p>Depending on the resource type, you can:</p>
                            <ul>
                                <li>View documents online</li>
                                <li>Download files to your computer</li>
                                <li>Print materials for classroom use</li>
                                <li>Share links with colleagues (where permitted)</li>
                            </ul>
                            <div class="tutorial-tip">
                                <i class="fas fa-lightbulb text-warning"></i>
                                <span>Resources open in a new tab so you can keep the resource library open while viewing multiple resources.</span>
                            </div>
                        </div>
                    </div>

                    <div class="tutorial-section mb-5">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-th-large text-danger me-2"></i>
                            Understanding Resource Cards
                        </h5>
                        <div class="tutorial-content">
                            <p>Each resource is displayed as a card with the following information:</p>
                            <ul>
                                <li><strong>Title:</strong> The name of the resource</li>
                                <li><strong>Category Badge:</strong> Shows which category the resource belongs to</li>
                                <li><strong>Quarter Badge:</strong> Indicates which quarter the resource is designed for</li>
                                <li><strong>Description:</strong> A brief overview of the resource content</li>
                                <li><strong>Date Added:</strong> When the resource was added to the system</li>
                            </ul>
                            <p>When you hover over a resource card, an overlay appears with:</p>
                            <ul>
                                <li>A more detailed description (if available)</li>
                                <li>The "Open Resource" button to access the resource</li>
                            </ul>
                        </div>
                    </div>

                    <div class="tutorial-section">
                        <h5 class="tutorial-heading">
                            <i class="fas fa-question-circle text-secondary me-2"></i>
                            Need More Help?
                        </h5>
                        <div class="tutorial-content">
                            <p>If you need additional assistance with Learning Resource Materials:</p>
                            <ul>
                                <li>Check other tutorials in the Help Center</li>
                                <li>Contact your school's Teacher Admin for help</li>
                                <li>If you can't find a specific resource, ask your Teacher Admin to request it</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tutorial Navigation -->
                    <div class="tutorial-nav">
                        <a href="{{ route('teacher.help.tutorial', 'reports') }}" class="tutorial-nav-btn prev">
                            <i class="fas fa-arrow-left"></i> Previous: Reports
                        </a>
                        <a href="{{ route('teacher.help.tutorial', 'faq') }}" class="tutorial-nav-btn next">
                            Next: FAQ <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tutorial-section {
        border-left: 3px solid #e9ecef;
        padding-left: 20px;
    }
    
    .tutorial-heading {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
    }
    
    .tutorial-content {
        color: #555;
    }
    
    .tutorial-tip {
        background-color: #fff8e1;
        border-left: 3px solid #ffc107;
        padding: 10px 15px;
        margin-top: 15px;
        border-radius: 4px;
        display: flex;
        align-items: center;
    }
    
    .tutorial-tip i {
        margin-right: 10px;
        font-size: 18px;
    }
</style>

<!-- Include the tutorial navigation CSS -->
<link rel="stylesheet" href="{{ asset('css/tutorial-nav.css') }}">
@endsection
