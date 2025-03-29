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
            
            console.log('Form validation passed, submitting...');
            return true;
        });
    } else {
        console.warn('mapehTabs element not found in the page');
    }
}); 