import 'slick-slider'
import $ from 'jquery'
import getListener from './loadMore'

const gallery = $('.block-trick-gallery')
const imageHighLight = $('.image-highlight')

const loadMoreComment = document.getElementById('loadMoreComments')

if (null !== loadMoreComment) {
  loadMoreComment.addEventListener('click', getListener(loadMoreComment))
}

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
