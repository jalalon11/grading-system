<!-- Edit Assessment Modal -->
<div class="modal fade" id="editAssessmentModal" tabindex="-1" aria-labelledby="editAssessmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header py-3 bg-white">
                    <h5 class="modal-title mb-0 fw-bold text-primary" id="editAssessmentModalLabel">
                        <i class="fas fa-edit me-2"></i> Edit Assessment
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAssessmentForm" method="POST" action="{{ route('teacher.grades.update-assessment') }}">
                        @csrf
                        <input type="hidden" id="edit_subject_id" name="subject_id">
                        <input type="hidden" id="edit_term" name="term">
                        <input type="hidden" id="edit_grade_type" name="grade_type">
                        <input type="hidden" id="edit_old_assessment_name" name="old_assessment_name">
                        <input type="hidden" id="edit_is_mapeh" name="is_mapeh" value="0">
                        <!-- Component ID is only used for MAPEH subjects -->
                        <input type="hidden" id="edit_component_id" name="component_id" value="">

                        <div class="mb-3">
                            <label for="edit_assessment_name" class="form-label">Assessment Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_assessment_name" name="assessment_name" required>
                            <div class="form-text">Provide a descriptive name for this assessment.</div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_max_score" class="form-label">Maximum Score <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_max_score" name="max_score" min="0.1" step="0.1" required>
                            <div class="form-text">The maximum possible score for this assessment.</div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Note:</strong> Editing an assessment will update all student grades for this assessment.
                        </div>

                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
