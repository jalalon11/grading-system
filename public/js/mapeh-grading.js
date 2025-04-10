/**
 * MAPEH Grading System JavaScript
 * Handles the UI interaction for grading MAPEH subjects with components
 */

$(document).ready(function() {
    console.log('MAPEH grading script loaded');

    // Ensure Bootstrap tabs are properly initialized
    $('#mapehTabs button[data-bs-toggle="tab"]').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
        console.log('Tab clicked:', $(this).attr('id'));
    });

    // Initialize first tab
    if ($('#mapehTabs button').length > 0) {
        console.log('Tab count:', $('#mapehTabs button').length);
        console.log('Active tab:', $('#mapehTabs button.active').attr('id'));
    }

    // Handle score validation for MAPEH components
    $('.component-score').on('input', function() {
        var score = parseFloat($(this).val());
        var max = parseFloat($(this).data('max'));

        if (score !== '' && !isNaN(score)) {
            if (score >= 0 && score <= max) {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $(this).siblings('.score-validation').find('.fa-check').removeClass('d-none');
                $(this).siblings('.score-validation').find('.fa-exclamation-triangle').addClass('d-none');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
                $(this).siblings('.score-validation').find('.fa-check').addClass('d-none');
                $(this).siblings('.score-validation').find('.fa-exclamation-triangle').removeClass('d-none');
            }
        } else {
            $(this).removeClass('is-valid is-invalid');
            $(this).siblings('.score-validation').find('.fa-check').addClass('d-none');
            $(this).siblings('.score-validation').find('.fa-exclamation-triangle').addClass('d-none');
        }
    });

    // Fill with zeros - MAPEH component
    $('.fill-zeros').on('click', function() {
        var componentId = $(this).data('component');
        console.log('Fill zeros for component:', componentId);
        $(`input[data-component="${componentId}"]`).val(0).trigger('input');
    });

    // Fill with perfect scores - MAPEH component
    $('.fill-perfect').on('click', function() {
        var componentId = $(this).data('component');
        var maxScore = $(this).data('max');
        console.log('Fill perfect scores for component:', componentId, 'max:', maxScore);
        $(`input[data-component="${componentId}"]`).val(maxScore).trigger('input');
    });

    // Transfer scores to all other MAPEH components
    $('.transfer-to-all').on('click', function() {
        var sourceComponentId = $(this).data('component');
        console.log('Transferring scores from component:', sourceComponentId, 'to all other components');

        // Get all student scores from source component
        var sourceScores = {};
        $(`input[data-component="${sourceComponentId}"]`).each(function() {
            var studentId = $(this).attr('id').split('_').pop();
            var score = $(this).val();
            if (score !== '') {
                sourceScores[studentId] = score;
            }
        });

        // Apply these scores to all other components
        $('.component-score').each(function() {
            var componentId = $(this).data('component');
            if (componentId != sourceComponentId) {
                var studentId = $(this).attr('id').split('_').pop();
                if (sourceScores[studentId] !== undefined) {
                    $(this).val(sourceScores[studentId]).trigger('input');
                }
            }
        });

        // Show confirmation message
        alert('Scores have been transferred from ' +
              $('#mapehTabs button[data-bs-target="#' +
              $(`input[data-component="${sourceComponentId}"]`).first().closest('.tab-pane').attr('id') +
              '"]').text().trim() + ' to all other components.');
    });

    // Handle MAPEH form validation
    function validateMapehForm() {
        var validForm = true;
        var invalidInputs = [];

        $('.component-score').each(function() {
            var score = $(this).val();
            var max = $(this).data('max');
            var component = $(this).data('component');
            var studentId = $(this).attr('id').split('_').pop();

            if (score === '' || isNaN(parseFloat(score)) || parseFloat(score) < 0 || parseFloat(score) > max) {
                $(this).addClass('is-invalid');
                validForm = false;
                invalidInputs.push({
                    component: component,
                    student: studentId,
                    value: score,
                    max: max
                });
            }
        });

        if (!validForm) {
            console.error('Invalid inputs found:', invalidInputs);
        }

        return validForm;
    }

    // Add MAPEH validation to the form submit
    if ($('#mapehTabs').length > 0) {
        $('#batchGradeForm').on('submit', function(e) {
            console.log('Form submitted, validating...');

            // Check if the form is already being submitted
            if ($(this).data('submitting')) {
                console.log('Form already being submitted, preventing duplicate submission');
                e.preventDefault();
                return false;
            }

            if (!validateMapehForm()) {
                e.preventDefault();
                alert('Please check your entries. Some scores are invalid or missing.');
                return false;
            }

            // Make sure we have at least one component with scores
            var hasScores = false;
            $('.component-score').each(function() {
                var score = $(this).val();
                if (score !== '' && !isNaN(parseFloat(score))) {
                    hasScores = true;
                    return false; // Break the loop once we find at least one valid score
                }
            });

            if (!hasScores) {
                e.preventDefault();
                alert('Please select at least one MAPEH component and enter scores.');
                return false;
            }

            // Mark the form as being submitted and disable the submit button
            $(this).data('submitting', true);
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');
            submitBtn.prop('disabled', true);

            console.log('Form validation passed, submitting...');
            return true;
        });
    } else {
        console.warn('mapehTabs element not found in the page');
    }
});