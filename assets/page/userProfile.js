import Modal from 'bootstrap.native'


const submitFormButton = document.getElementById('submitForm')
const avatarForm = document.getElementById('js-handle-avatar')
const handleUrl = avatarForm.getAttribute('data-avatar-url')

avatarForm.addEventListener('submit', function (e) {
    e.preventDefault()

    const formData = new FormData(avatarForm)


        fetch(handleUrl, {
            method: 'post',
            body: formData
        })
        .then(response => response.text())
        .then(response => console.log(response))
})