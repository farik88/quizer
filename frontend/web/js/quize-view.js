$(document).ready(function(){
    
    var submit_button = $('#submit-answer').get();
    
    variantsButtonsClick();
    chartJsStart();
    
    function variantsButtonsClick()
    {
        var buttons = $('.quizes-view  .buttons button').get();
        $(buttons).on('click', function(){
            var variant_btn = this;
            var variant_id = $(variant_btn).data('variant');
            if(changeRadioVariant(variant_id)){
                enableVariantButton(variant_btn);
                enableSubmitButton();
            }
        });
        buttons = $('.quize-results  .buttons button').get();
        $(buttons).on('click', function(){
            var variant_btn = this;
            var variant_id = $(variant_btn).data('variant');
            if(changeRadioVariant(variant_id)){
                enableVariantButton(variant_btn);
                enableSubmitButton();
            }
        });
    }
    
    function changeRadioVariant(id)
    {
        var radio = $('.inputs input[type="radio"][value="'+id+'"]').get()[0];
        radio.checked = true;
        return true;
    }
    
    function enableVariantButton(button)
    {
        $('.quizes-view .buttons .btn-success').removeClass('btn-success').addClass('btn-default');
        $('.quize-results .buttons .btn-success').removeClass('btn-success').addClass('btn-default');
        $(button).removeClass('btn-default');
        $(button).addClass('btn-success');
    }
    
    function enableSubmitButton()
    {
        $(submit_button).prop('disabled', false);
        $(submit_button).addClass('btn-success');
        $(submit_button).removeClass('dissabled');
    }
    
    function disableSubmitButton()
    {
        $(submit_button).addClass('dissabled');
        $(submit_button).removeClass('btn-success');
        $(submit_button).prop('disabled', true);
    }
    
    function chartJsStart()
    {
        var pie = $('#quizeChart').get()[0];
        if(pie){
            var quize_id = $(pie).data('quize');
            if (quize_id){
                $.ajax({
                    type: 'POST',
                    url: window.location.origin + '/quizes/get-answers-data-for-chart',
                    data: {
                        id : quize_id
                    },
                    success: function (data) {
                        var data_vaues = data.vaues;
                        var data_labels = data.labels;
                        var data_options = {
                            legend : {
                                position : 'bottom'
                            }
                        };
                        var data = {
                            datasets: [{
                                data: data_vaues,
                                backgroundColor: [
                                    '#0099e5','#ff4c4c','#34bf49','#fbb034','#472f92','#da5a47','#237f52','#de0f17','#ff4c4c','#34bf49','#fbb034','#472f92','#da5a47','#237f52','#de0f17'
                                ]
                            }],
                            labels: data_labels
                        };
                        var myPieChart = new Chart(pie, {
                            type: 'pie',
                            data: data,
                            options: data_options
                        });
                    },
                    error: function (XMLHttpRequest) {
                        alert (XMLHttpRequest.responseText);
                    }
                });
            }else{
                alert('Error! Cant load quize id data from HTML node.');
            }
        }
    }
});