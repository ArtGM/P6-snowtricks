export default function getListener (button) {
  const target = button.getAttribute('data-target')
  const parameters = button.getAttribute('data-filter')
  console.log(parameters)
  const targetContainer = document.getElementById(target)
  return async (event) => {
    const element = event.target
    let url = `${element.dataset.url}/${element.dataset.page}/`
    if (element.dataset.filter) {
      url += element.dataset.filter
    }

    const headers = new Headers()
    headers.append('snow-request', 'true')
    const fetchOptions = {
      method: 'GET',
      headers: headers,
    }
    targetContainer.classList.add('loading')

    const response = await fetch(url, fetchOptions)

    const data = await response.json()
    const template = await data.html
    const endMessage = await data.message

    if (template !== '') {
      targetContainer.innerHTML += template
      element.dataset.page++
      if (endMessage !== '') {
        element.innerText = endMessage
        element.setAttribute('disabled', 'disabled')
      }
    }
    targetContainer.classList.remove('loading')
  }
}
