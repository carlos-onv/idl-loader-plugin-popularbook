(function ($) {
    var book_junky_init = {
        init: function () {
            this.events.viewMoreRecentReviews();
            this.events.showCommentForm();
            this.events.selectFilterCate();
            this.events.filterPriceButtonClick();
            this.events.selectFilterAge();
            this.events.selectFilterRating();
            this.handles.showRangePrice();
        },
        events: {
            viewMoreRecentReviews: function () {
                $(document).on("click", ".bj-view-more-rv", function (e) {
                    $(this).parent().remove();
                    e.preventDefault();
                    console.log('1111');
                    $(".review-item").css("display", "block");
                });
            },
            showCommentForm: function () {
                var area = $(".woocommerce-Reviews .comment-form-comment textarea");
                $(document).on('click', '.bj-write-cmt-btn', function (e) {
                    e.preventDefault();
                    $(this).addClass("bj-hidden");
                    $('.bj-comment-form').addClass("bj-active");
                    setTimeout(function () {
                        area.focus();
                    }, 200);

                });
            },
            selectFilterCate:function () {
                $('.bj-ft-cat-item').on('change','input',function (e) {
                    var cate_list = [];
                    e.preventDefault();
                    $('.bj-ft-check-cate:checked').each(function(){
                        cate_list.push($(this).val());
                    });
                    $('input[name="bj_tax_product_cat"]').val(cate_list.join());
                    book_junky_init.events.submitFilterForm();
                });
            },
            selectFilterAge:function () {
                $('.bj-ft-age-item').on('change','input',function (e) {
                    var age_list = [];
                    e.preventDefault();
                    $('.bj-ft-input-age:checked').each(function(){
                        age_list.push($(this).val());
                    });
                    $('input[name="bj_meta_ef3-age_accordant"]').val(age_list.join());
                    book_junky_init.events.submitFilterForm();
                });
            },
            selectFilterRating:function () {
                $('.bj-ft-rating-item').on('change','input',function (e) {
                    var rating_list = [];
                    e.preventDefault();
                    $('.bj-ft-input-rating:checked').each(function(){
                        rating_list.push($(this).val());
                    });
                    $('input[name="bj_meta__wc_average_rating"]').val(rating_list.join());
                    book_junky_init.events.submitFilterForm();
                });
            },
            filterPriceButtonClick:function () {
                $(document).on('click','.bj-ft-price-button',function (e) {
                    e.preventDefault();
                    book_junky_init.events.submitFilterForm();
                })
            },
            submitFilterForm:function () {
                $(".bj-ft-form").submit();
            }
        },
        handles: {
            showRangePrice: function () {
                var _range = $("#bj-range-price"),
                    _bj_price_val = $(".bj-range-price-val"),
                    _bj_ft_price = $(".bj-ft-price");
                if (!_range.length || typeof _range.rangeSlider !== "function") return;
                var max_value = _bj_ft_price.attr("data-max");
                var bj_min_ = $(".bj-range-min-price-val");
                var bj_max_ = $(".bj-range-max-price-val");
                _range.rangeSlider({
                        bounds: {min: 0, max: max_value},
                        formatter: function (val) {
                            return _bj_ft_price.attr("data-currency") + Math.round(val);
                        },
                        defaultValues: {min: bj_min_.val(), max: bj_max_.val()},
                        range: {min: 0, max: max_value}
                    }
                ).bind("valuesChanged", function (e, data) {
                    bj_min_.val(Math.round(data.values.min));
                    bj_max_.val(Math.round(data.values.max));
                });
            },


        }

    };
    book_junky_init.init();
})(jQuery);
