/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import bsCustomFileInput from 'bs-custom-file-input'
import Toast from 'bootstrap.native/dist/components/toast-native.esm.js'
import Collapse from 'bootstrap.native/dist/components/collapse-native.esm.js'
import './scss/app.scss'

import { deleteModal, getTricks } from './page/homepage'

bsCustomFileInput.init()
const navbar = document.querySelectorAll('.navbar-toggler')
console.log(navbar)

Array.from(navbar).map(
  collapseTrigger => {
    new Collapse(collapseTrigger,
      {
        parent: collapseTrigger.querySelector('[data-toggle="collapse"]'),
      })
  })

const flashToast = document.getElementById('homeToast')
if (flashToast) {
  const flashInit = new Toast(flashToast, {
    autohide: true,
    delay: 5000,
  })
  flashInit.show()
}

deleteModal()
getTricks()
