$(function(){
    $.fn.sliderPlayer = function(){

        // DOM缓存
        var $this = $(this);
        var DOM = {
            slider_list: $this.find('.slider-list'),
            slider_item: $this.find('.slider-list li'),
            slider_btn: $('.slider-btn .btn-item')
        }

        // 轮播参数
        var slider_length = DOM.slider_item.length;
        var slider_width = DOM.slider_item.width();
        var slider_index = slider_length;
        var slider_time = 3000;

        // 切换效果
        var slideTo = function(){              
            if (slider_index == slider_length*2+1) {
                DOM.slider_list.css({
                    "left" : '-' + (slider_width*slider_length) + "px"
                });
                slider_index = slider_length+1; 
            }else if(slider_index == slider_length-2){
                DOM.slider_list.css({
                    "left" : "-" + slider_width * (slider_length*2-1) + "px"
                });
                slider_index = slider_length*2-2;
            }
            DOM.slider_list.stop().animate({
                "left" : "-" + slider_index * slider_width + "px"
            },500);

	        DOM.slider_btn.eq(slider_index%4).addClass("active").siblings().removeClass('active');
        }; 

        // 无缝拼接
        DOM.slider_list.html(
            DOM.slider_list.html()+DOM.slider_list.html()+DOM.slider_list.html()
        ).css({
            "left" : "-" + slider_width * slider_length + "px"
        });

        var palyerTimer = setInterval(function(){
            slider_index += 1;
            slideTo();
        },slider_time);

        $this.hover(function(){
            clearInterval(palyerTimer);
        },function(){
            palyerTimer = setInterval(function(){
                slider_index += 1;
                slideTo();
            },slider_time);
        });

        // 滑动到指定项
        DOM.slider_btn.hover(function(){
            clearInterval(palyerTimer);
        	slider_index += $(this).index()-slider_index%4;
            slideTo();
        },function(){
            palyerTimer = setInterval(function(){
                slider_index += 1;
                slideTo();
            },slider_time);
        });
    };
})