 // set and cache variables
// var w, container, carousel, item, radius, itemLength, rY, ticker;
// var mouseX = 0;
// var mouseY = 0;
// var mouseZ = 0;
// var addX = 0;
// fps counter created by: https://gist.github.com/sharkbrainguy/1156092,
// no need to create my own :)
// var fps_counter = {
//   tick: function () {
//     // this has to clone the array every tick so that
//     // separate instances won't share state 
//     this.times = this.times.concat(+new Date());
//     var seconds, times = this.times;
//     if (times.length > this.span + 1) {
//       times.shift(); // ditch the oldest time
//       seconds = (times[times.length - 1] - times[0]) / 1000;
//       return Math.round(this.span / seconds);
//     }
//     else return null;
//   },
//   times: [],
//   span: 8
// };
// var counter = Object.create(fps_counter);
// var container = Object.create(carousel);
// function init() {
//   w = $(window);
//   container = $('#contentContainer');
//   carousel = $('#carouselContainer');
//   item = $('.carouselItem');
//   itemLength = $('.carouselItem').length;
//   fps = $('#fps');
//   rY = 360 / itemLength;
//   radius = Math.round((250) / Math.tan(Math.PI / itemLength));
//   // set container 3d props
//   TweenMax.set(container, { perspective: 600 })
//   TweenMax.set(carousel, { z: -(radius) })
//   // create carousel item props
//   for (var i = 0; i < itemLength; i++) {
//     var $item = item.eq(i);
//     var $block = $item.find('.carouselItemInner');
//     //thanks @chrisgannon!        
//     TweenMax.set($item, { rotationY: rY * i, z: radius, transformOrigin: "50% 50% " + -radius + "px" });
//     animateIn($item, $block)
//   }
//   // set mouse x and y props and looper ticker
//   window.addEventListener("mousemove", onMouseMove, false);
//   ticker = setInterval(looper, 1000 / 60);
// }
// function animateIn($item, $block) {
//   var $nrX = 360 * getRandomInt(2);
//   var $nrY = 360 * getRandomInt(2);
//   var $nx = -(2000) + getRandomInt(4000)
//   var $ny = -(2000) + getRandomInt(4000)
//   var $nz = -4000 + getRandomInt(4000)
//   var $s = 1.5 + (getRandomInt(10) * .1)
//   var $d = 1 - (getRandomInt(8) * .1)
//   TweenMax.set($item, { autoAlpha: 1, delay: $d })
//   TweenMax.set($block, { z: $nz, rotationY: $nrY, rotationX: $nrX, x: $nx, y: $ny, autoAlpha: 0 })
//   TweenMax.to($block, $s, { delay: $d, rotationY: 0, rotationX: 0, z: 0, ease: Expo.easeInOut })
//   TweenMax.to($block, $s - .5, { delay: $d, x: 0, y: 0, autoAlpha: 1, ease: Expo.easeInOut })
// }
// function onMouseMove(event) {
//   mouseX = -(-(window.innerWidth * .5) + event.pageX) * .0025;
//   mouseY = -(-(window.innerHeight * .5) + event.pageY) * .01;
//   mouseZ = -(radius) - (Math.abs(-(window.innerHeight * .5) + event.pageY) - 200);
// }
// // loops and sets the carousel 3d properties
// function looper() {
//   addX += mouseX
//   TweenMax.to(carousel, 1, { rotationY: addX, rotationX: mouseY, ease: Quint.easeOut })
//   TweenMax.set(carousel, { z: mouseZ })
//   fps.text('Framerate: ' + counter.tick() + '/60 FPS')
// }
// function getRandomInt($n) {
//   return Math.floor((Math.random() * $n) + 1);
// } 
// $(function() {
//     var $horizontalCollapse = $('.js-horizontal-collapse');
//     $horizontalCollapse.each(function() {
//       var $hc = $(this);
//       var itemMinWidth = $hc.data('itemMinWidth');
//       var itemMaxWidth = $hc.data('itemMaxWidth');
//       var $items = $hc.find('.js-horizontal-collapse-item');
//       $items.each(function() {
//         var $item = $(this);
//         $item.css('min-width', itemMinWidth + 'px');
//         $item.css('width', itemMinWidth + 'px');
//         $item.css('max-width', itemMaxWidth + 'px');
//         $item.find('a').click(function(evt) {
//           evt.stopPropagation();
//         });
//         $item.on('keypress', function(evt) {
//           if(evt.keyCode === 13 || evt.keyCode === 32) {
//             $(this).trigger('click');
//           }
//         });
//         $item.on('mouseenter',function() {
//           var $this = $(this);
//           $this.siblings().removeClass('is-active');
//           $this.siblings().css('width', itemMinWidth + 'px');
//           $this.addClass('is-active');
//           $this.css('width', itemMaxWidth + 'px');
//         });
//         $item.on('mouseleave',function() {
//           var $this = $(this);
//           $this.siblings().removeClass('is-active');
//           $this.siblings().css('width', itemMinWidth + 'px');
//           $this.removeClass('is-active');
//           $this.css('width', itemMinWidth + 'px');
//         });
//         $item.on('click ',function() {
//           var $this = $(this);
//           $this.siblings().removeClass('is-active');
//           $this.siblings().css('width', itemMinWidth + 'px');
//           $this.toggleClass('is-active');
//           if ($this.hasClass('is-active')) {
//              $this.css('width', itemMaxWidth + 'px');
//           } else {
//             $this.css('width', itemMinWidth + 'px');
//           }
//         });
//         var $itemInner = $item.find('.js-horizontal-collapse-item-inner');
//         $itemInner.css('width', itemMaxWidth + 'px');
//       });
//     });
//   });