const getTricks = () => {
  const loadMoreButton = document.getElementById('loadMoreTricks')
  const homepageTricksList = document.getElementById('homeTricksList')
  const spinner = document.getElementById('spinner')

  loadMoreButton.addEventListener('click', event => {
    const element = event.target
    const url = `${element.dataset.url}/${element.dataset.page}`
    homepageTricksList.style.opacity = '0.5'
    spinner.classList.add('is-loading')
    fetch(url).then(
      response => response.json(),
    ).then(
      data => {
        const trickTemplate = data.html
        const endMessage = data.message
        if (trickTemplate !== '') {
          homepageTricksList.innerHTML += trickTemplate
          element.dataset.page++
          if (endMessage !== '') {
            element.innerText = endMessage
            element.setAttribute('disabled', 'disabled')
          }
        }
        spinner.classList.remove('is-loading')
        homepageTricksList.style.opacity = '1'
      },
    )
  })
}

export {
  getTricks,
}