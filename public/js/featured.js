var AutoChangeSlide = function(params) {
    var maxSlides = params.maxSlides;
    var currentSlide = params.startSlide;
    var element = params.element;

    this.setupTiming = function() {
        var t = this;
        return setInterval(function() {
            t.autoSwitch();
        }, 5000);
    };
        
    this.autoSwitch = function() {
        currentSlide++;
        if (currentSlide > maxSlides) {
            currentSlide = 1;
        }
        
        $('div#featuredArticles article').hide();
        
        var slide = currentSlide;
        $('div#featuredArticles article').each(function() {
            if ($(this).attr('value') == slide) {
                $(this).show();
            }
        });
    };
    
    this.firstSlide = function() {
        $('div#featuredArticles article').each(function() {
            $(this).hide();
            if ($(this).attr('value') == '1') {
                $(this).show();
            }
        });
    };
};
