import 'slick-slider'
import $ from 'jquery'

const gallery = $('.block-trick-gallery')
const imageHighLight = $('.image-highlight')

imageHighLight.slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  fade: true,
  asNavFor: gallery,
  responsive: [
    {
      breakpoint: 480,
      settings: {
        arrows: true,
        autoplay: true,
      },
    },
  ],
})

gallery.slick({
  slidesToShow: 5,
  slidesToScroll: 1,
  asNavFor: imageHighLight,
  dots: true,
  centerMode: true,
  focusOnSelect: true,
  responsive: [
    {
      breakpoint: 768,
      settings: 'unslick',
    },
  ],
})
