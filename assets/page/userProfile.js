import 'bootstrap/js/dist/modal'

const submitFormButton = document.getElementById('submitForm')
const avatarForm = document.getElementById('js-handle-avatar')

avatarForm.addEventListener('submit', function (e) {
    e.preventDefault()

    const formData = new FormData(avatarForm)


        fetch('/user/profile/handle-avatar/', {
            method: 'post',
            body: formData
        })
        .then(response => response.text())
        .then(response => console.log(response))
})