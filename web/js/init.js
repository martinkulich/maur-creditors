$(document).ready(function(){

    var modal_dialog_content_updated = false;

    $('[rel=tooltip]').tooltip(
    {
        animation: false,
        placement: 'right',
        template: '<div class="tooltip tooltip-error"><div class="tooltip-arrow tooltip-arrow-error"></div><div class="tooltip-inner tooltip-inner-error"></div></div>'
    });


    $('#modal_dialog').on('hide', function (event) {
        $('[rel=tooltip]').tooltip('hide');
        $(this).empty();
        $(this).css('margin-left', '-280px');
        event.stopPropagation();
    });

    $('.modal_link').click(function(event){
        if(modal_dialog_content_updated != true)
        {
            event.preventDefault();

            var target = '#modal_dialog';

            currentWidth = $(target).width();
            $result = $.get(this.href, function(data) {
                });
            $result.success(function(data, textStatus, xhr){
                var status = $(xhr).attr('status');

                if(status == 200)
                {

                    var withoutSubmenu = $(target).hasClass('without-submenu');
                    $(target).html(data);
                    modal_dialog_content_updated = true;

                    centerModal(target, currentWidth);

                    if(!$('body').hasClass('modal-open'))
                    {
                        if(withoutSubmenu)
                        {

                            $('body').addClass('without-submenu');
                        }

                        $(target).modal(
                        {
                            'show': true,
                            'backdrop': false,
                            'keyboard':false
                        }
                        );
                    }
                }

                if(status == 205)
                {
                    location.reload();
                }


            });

            $result.error(function(data, textStatus, xhr){
                if(xhr == 'Unauthorized')
                {
                    $(location).attr('href',login_url);
                }
            });
            return false;
        }
    });


    //odeslatni rezervacniho formulare
    $(".form-modal").submit(function(event) {
        /* stop form from submitting normally */
        event.preventDefault();
        if(modal_dialog_content_updated != true)
        {
            /* get some values from elements on the page: */
            var $form = $( this ),
            url = $form.attr('action');
            var target = '#modal_dialog';
            /* Send the data using post and put the results in a div */
            method  =$form.attr('method');
            if(method == 'post')
            {
                $result = $.post(
                    url,
                    $(this).serialize(),
                    function( data ) {});
            }
            else
            {
                $result = $.get(
                    url,
                    $(this).serialize(),
                    function( data ) {});
            }

            $result.success(function(data, textStatus, xhr){
                var status = $(xhr).attr('status');
                if(status == 200)
                {
                    $(target).html(data);
                    modal_dialog_content_updated = true;
                }

                if(status == 205)
                {
                    location.reload();
                }
            });

            $result.error(function(data, textStatus, xhr){
                if(xhr == 'Unauthorized')
                {
                    $(location).attr('href',login_url);
                }
            });
        }
        return false;
    });


    $('.date-link').click(function(event){
        $('html, body').animate({
            scrollTop: $('#'+this.rel).offset().top-100
        }, 500);
        event.preventDefault();
    });

    $('.reservation_detail_link').hover(function(){
        var link = $(this);
        link.html(link.attr('amount'));
    },function(){
        var link = $(this);
        link.html(link.attr('username'));
    }
    );
});

jQuery(function() {
    jQuery.support.placeholder = false;
    test = document.createElement('input');
    if('placeholder' in test) jQuery.support.placeholder = true;
});

$(function() {
    if(!$.support.placeholder) {

        var selector = 'input[type=text], input[type=password], input[defaultType=password]'
        var active = document.activeElement;
        $(selector).focus(function () {
            if ($(this).attr('placeholder') != '' && $(this).val() == $(this).attr('placeholder')) {
                $(this).val('').removeClass('hasPlaceholder');
            }
        }).blur(function () {
            if ($(this).attr('placeholder') != '' && ($(this).val() == '' || $(this).val() == $(this).attr('placeholder'))) {
                $(this).val($(this).attr('placeholder')).addClass('hasPlaceholder');
            }
        });
        $(selector).blur();
        $(active).focus();
        $('form').submit(function () {
            $(this).find('.hasPlaceholder').each(function() {
                $(this).val('');
            });
        });
    }
});


function convertType(elem, newType)
{
    var input = $(elem).clone();
    input.type = newType;

    elem.parentNode.replaceChild(input, elem);
    return input;
}
function centerModal(modal, currentWidth){
    //request data for centering
    var newWidth = $(modal).width();
    var div = (currentWidth - newWidth)/2;
    //centering
    $(modal).animate({
        "margin-left": '+='+div
    }, 100);

}

function updateSelectBox(url, selector, target, paramName, filter)
{
    var defaultValue = jQuery('#'+target).val();
    if(defaultValue == '')
    {
        defaultValue = 0;
    }

    paramValue = jQuery('#'+selector).val();

    if(paramValue == '')
    {
        paramValue = 0;
    }


    url += '?' +paramName+'='+paramValue+'&default='+defaultValue;

    if(filter != undefined)
    {
        url += '&filter='+filter;
    }

    jQuery.get(url, {}, function(data){
        jQuery('#'+target).replaceWith(data);
    //        jQuery('#'+selector).attr('id', selector);
    });
}

function calculateContractClosingAmount(selector,unsettledTarget, balanceReductionTarget, settlementTypeSelector, url)
{
    var date = $(selector).val();

    if($(settlementTypeSelector).length !=0)
    {
        settlementType = $(settlementTypeSelector).val();
    }


    $result = $.get(
        url,
        {
            date: date,
            settlement_type: settlementType
        },
        function(data) {
        });
    $result.success(function(data, textStatus, xhr){
        $(unsettledTarget).val(data.unsettled);
        $(balanceReductionTarget).val(data.balance_reduction);
    });
}


function calculateSettlement(settlement_id, settlement_type, contractSelector, contract_id, dateSelector, settlementTypeSelector, target, url, checkboxSelector)
{
    var date = $(dateSelector).val();
    if($(contractSelector).length !=0)
    {
        contract_id = $(contractSelector).val();
    }

    var checked = false;
    if($(checkboxSelector).length !=0)
    {
        checked = $(checkboxSelector).is(':checked');
    }

    if($(settlementTypeSelector).length !=0)
    {
        settlement_type = $(settlementTypeSelector).val();
    }

    if(!checked)
    {
        $result = $.get(
            url,
            {
                date: date,
                contract_id: contract_id,
                settlement_id: settlement_id,
                settlement_type: settlement_type
            }
            );

        $result.success(function(data, textStatus, xhr){
            $(target).val(data.amount);
        });
    }
}

function closingActionUpdate()
{
    var action  = $('#contract_action').val();
    if(action == 'payment')
    {
        $('#settlement').hide();
        $('#payment').show()
    }
    else
    {
        $('#settlement').show();
        $('#payment').hide()
    }

}