var AutoChangeSlide = function(params) {
    var maxSlides = params.maxSlides;
    var startSlide = params.startSlide;
    var currentSlide = startSlide;

    this.setupTiming = function() {
        return setInterval(function(){autoSwitch()}, 5000);
    };

    var autoSwitch = function() {
        currentSlide++;
        if (currentSlide > maxSlides) {
            currentSlide = 1;
        }
        $('div#featured div#featuredArticles article').hide();
        $('div#featured div#featuredArticles article').each(function() {
            if ($(this).attr('value') == currentSlide) {
                $(this).show();
            }
        });
    };
};
