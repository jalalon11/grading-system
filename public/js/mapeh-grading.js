/**
 * MAPEH Grading System JavaScript
 * Handles the UI interaction for grading MAPEH subjects with components
 */

$(document).ready(function() {
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
        var componentIndex = $(this).data('component');
        $(`input[data-component="${componentIndex}"]`).val(0).trigger('input');
    });
    
    // Fill with perfect scores - MAPEH component
    $('.fill-perfect').on('click', function() {
        var componentIndex = $(this).data('component');
        var maxScore = $(this).data('max');
        $(`input[data-component="${componentIndex}"]`).val(maxScore).trigger('input');
    });
    
    // Handle MAPEH form validation
    function validateMapehForm() {
        var validForm = true;
        
        $('.component-score').each(function() {
            var score = $(this).val();
            var max = $(this).data('max');
            
            if (score === '' || isNaN(parseFloat(score)) || parseFloat(score) < 0 || parseFloat(score) > max) {
                $(this).addClass('is-invalid');
                validForm = false;
            }
        });
        
        return validForm;
    }
    
    // Add MAPEH validation to the form submit
    if ($('#mapehTabs').length > 0) {
        $('#batchGradeForm').on('submit', function(e) {
            if (!validateMapehForm()) {
                e.preventDefault();
                alert('Please check your entries. Some scores are invalid or missing.');
            }
        });
    }
}); 