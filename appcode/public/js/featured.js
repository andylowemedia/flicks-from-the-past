var AutoChangeSlide = function(params) {
    this.maxSlides = params.maxSlides;
    this.currentSlide = params.startSlide;

    this.setupTiming = function() {
        var t = this;
        return setInterval(function() {
            t.autoSwitch();
        }, 5000);
    };
        
    this.autoSwitch = function() {
        this.currentSlide++;
        if (this.currentSlide > this.maxSlides) {
            this.currentSlide = 1;
        }
        
        $('div#featured div#featuredArticles article').hide();
        
        var t = this;
        $('div#featured div#featuredArticles article').each(function() {
 
            if ($(this).attr('data-value') == t.currentSlide) {
                $(this).show();
            }
        });
    };
};
