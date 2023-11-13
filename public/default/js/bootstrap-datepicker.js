var BootstrapDatepicker = function() {
    var t = function() {
        $("#m_datepicker_1, #m_datepicker_1_validate").datepicker({
            todayHighlight: !0,
            autoclose: true,
            format: 'yyyy-mm-dd',
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }), $("#m_datepicker_1_modal").datepicker({
            todayHighlight: !0,
            autoclose: true,
            format: 'yyyy-mm-dd',
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }), $("#m_datepicker_2, #m_datepicker_2_validate").datepicker({
            todayHighlight: !0,
            autoclose: true,
            format: 'yyyy-mm-dd',
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }), $("#m_datepicker_2_modal").datepicker({
            todayHighlight: !0,
            autoclose: true,
            format: 'yyyy-mm-dd',
            orientation: "bottom left",
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }), $("#m_datepicker_3, #m_datepicker_3_validate").datepicker({
            todayBtn: "linked",
            clearBtn: !0,
            format: 'yyyy-mm-dd',
            todayHighlight: !0,
            autoclose: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }), $("#m_datepicker_3_modal").datepicker({
            todayBtn: "linked",
            format: 'yyyy-mm-dd',
            clearBtn: !0,
            todayHighlight: !0,
            autoclose: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }), $("#m_datepicker_4_1").datepicker({
            orientation: "top left",
            format: 'yyyy-mm-dd',
            todayHighlight: !0,
            autoclose: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }), $("#m_datepicker_4_2").datepicker({
            orientation: "top right",
            format: 'yyyy-mm-dd',
            todayHighlight: !0,
            autoclose: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }), $("#m_datepicker_4_3").datepicker({
            orientation: "bottom left",
            format: 'yyyy-mm-dd',
            todayHighlight: !0,
            endDate: '+0d',
            autoclose: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }), $("#m_datepicker_4_4").datepicker({
            orientation: "bottom right",
            format: 'yyyy-mm-dd',
            todayHighlight: !0,
            autoclose: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }), $("#m_datepicker_5").datepicker({
            todayHighlight: !0,
            format: 'yyyy-mm-dd',
            endDate: '+0d',
            autoclose: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        }), $("#m_datepicker_6").datepicker({
            todayHighlight: !0,
            format: 'yyyy-mm-dd',
            autoclose: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        })
    };
    return {
        init: function() {
            t()
        }
    }
}();
jQuery(document).ready(function() {
    BootstrapDatepicker.init()
});