const getTricks = () => {
  const loadMoreButton = document.getElementById('loadMoreTricks')
  const homepageTricksList = document.getElementById('homeTricksList')
  const spinner = document.querySelector('.spinner-container')
  if (null !== loadMoreButton) {
    loadMoreButton.addEventListener('click', async (event) => {
        const element = event.target
        const url = `${element.dataset.url}/${element.dataset.page}`
        homepageTricksList.style.opacity = '0.5'
        spinner.style.display = 'block'

        const response = await fetch(url)
        const data = await response.json()
        const trickTemplate = await data.html
        const endMessage = await data.message

        if (trickTemplate !== '') {
          homepageTricksList.innerHTML += trickTemplate
          element.dataset.page++
          if (endMessage !== '') {
            element.innerText = endMessage
            element.setAttribute('disabled', 'disabled')
          }
        }
        homepageTricksList.style.opacity = '1'
      },
    )
    spinner.style.display = 'none'
  }
}

export {
  getTricks,
}