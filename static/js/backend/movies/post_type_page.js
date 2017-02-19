(function ($) {
    var PostTypePage = {
        subtitle: {
            fieldSelector: '#subtitle',
            labelSelector: '#subtitle-label',

            init: function () {
                var self = PostTypePage.subtitle;

                self.showLabelIfSubtitleHasNotValue();
                self.initFocusAction();
                self.initLostFocusAction();
            },

            showLabelIfSubtitleHasNotValue: function () {
                var self = PostTypePage.subtitle;

                self.onShowLabelEvent();
            },

            initFocusAction: function () {
                 var self = PostTypePage.subtitle;
                
                $(self.fieldSelector).on('focus', self.onHideLabelEvent)
            },

            initLostFocusAction: function () {
                 var self = PostTypePage.subtitle;

                $(self.fieldSelector).on('focusout', self.onShowLabelEvent)
            },

            onHideLabelEvent: function () {
                 var self = PostTypePage.subtitle;

                if ($(this).val()) {
                    return true;
                }

                $(self.labelSelector).hide();
            },

            onShowLabelEvent: function () {
                 var self = PostTypePage.subtitle;

                if ($(self.fieldSelector).val()) {
                    return true;
                }

                $(self.labelSelector).show();
            }
        },

        price: {
            fieldSelector: '#price',
            labelSelector: '#price-label',

            init: function () {
                var self = PostTypePage.price;

                self.initKeyUpAction()
            },

            initKeyUpAction: function () {
                var self = PostTypePage.price;

                $(self.fieldSelector).on('keyup', self.onLeavePriceFormatEvent);
            },

            onLeavePriceFormatEvent: function () {
                var value = $(this).val();

                var testResult = /^\d{0,10}(\.\d{0,2})?$/.test(value);

                if (!testResult){
                    console.log("Invalid input!");
                    value = value.substring(0, value.length - 1);
                }

                $(this).val(value);
            }
        },

        init: function () {
             var self = PostTypePage;

            self.subtitle.init();
            self.price.init();
        }
    }
    
    $(document).ready(function () {
        PostTypePage.init();
    })
})(jQuery)
