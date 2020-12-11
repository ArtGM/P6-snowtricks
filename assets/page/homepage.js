const getTricks = () => {
  const loadMoreButton = document.getElementById('loadMoreTricks')
  const homepageTricksList = document.getElementById('homeTricksList')

  loadMoreButton.addEventListener('click', event => {
    const element = event.target
    const url = `${element.dataset.url}/${element.dataset.page}`
    homepageTricksList.classList.add('is-loading')
    fetch(url).then(
      response => response.json(),
    ).then(
      data => {
        const trickTemplate = data.html
        const endMessage = data.message
        homepageTricksList.classList.remove('is-loading')
        if (trickTemplate !== '') {
          homepageTricksList.innerHTML += trickTemplate
          element.dataset.page++
          if (endMessage !== '') {
            element.innerText = endMessage
            element.setAttribute('disabled', 'disabled')
          }
        }
      },
    )
  })
}

export {
  getTricks,
}