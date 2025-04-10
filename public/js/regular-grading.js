/**
 * Regular Subject Grading System JavaScript
 * Handles the UI interaction for grading regular subjects
 */

$(document).ready(function() {
    // Handle score validation for regular subjects
    $('.grade-input').on('input', function() {
        var score = parseFloat($(this).val());
        var max = parseFloat($(this).data('max'));
        var studentId = $(this).attr('id').replace('score', '');

        if (score !== '' && !isNaN(score)) {
            if (score >= 0 && score <= max) {
                $(this).removeClass('is-invalid').addClass('is-valid');
                $(`#validScore${studentId}`).removeClass('d-none');
                $(`#invalidScore${studentId}`).addClass('d-none');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
                $(`#validScore${studentId}`).addClass('d-none');
                $(`#invalidScore${studentId}`).removeClass('d-none');
            }
        } else {
            $(this).removeClass('is-valid is-invalid');
            $(`#validScore${studentId}`).addClass('d-none');
            $(`#invalidScore${studentId}`).addClass('d-none');
        }
    });

    // Fill with zeros - Regular subject
    $('#fillWithZeros').on('click', function() {
        $('.grade-input').val(0).trigger('input');
    });

    // Fill with perfect scores - Regular subject
    $('#applyPerfect').on('click', function() {
        $('.grade-input').each(function() {
            var max = $(this).data('max');
            $(this).val(max).trigger('input');
        });
    });

    // Handle regular form validation
    function validateRegularForm() {
        var validForm = true;

        $('.grade-input').each(function() {
            var score = $(this).val();
            var max = $(this).data('max');

            if (score === '' || isNaN(parseFloat(score)) || parseFloat(score) < 0 || parseFloat(score) > max) {
                $(this).addClass('is-invalid');
                validForm = false;
            }
        });

        return validForm;
    }

    // Add regular validation to the form submit if this is not a MAPEH form
    if ($('#mapehTabs').length === 0) {
        $('#batchGradeForm').on('submit', function(e) {
            // Check if the form is already being submitted
            if ($(this).data('submitting')) {
                e.preventDefault();
                return false;
            }

            if (!validateRegularForm()) {
                e.preventDefault();
                alert('Please check your entries. Some scores are invalid or missing.');
                return false;
            }

            // Mark the form as being submitted and disable the submit button
            $(this).data('submitting', true);
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');
            submitBtn.prop('disabled', true);

            // Allow form submission
            return true;
        });
    }
});