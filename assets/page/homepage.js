const getTricks = () => {
  const loadMoreButton = document.getElementById('loadMoreTricks')
  const homepageTricksList = document.getElementById('homeTricksList')

  loadMoreButton.onclick = function () {
    const url = `${this.dataset.url}/${this.dataset.page}`
    homepageTricksList.style.opacity = '0.5'
    console.log(url)
    fetch(url).then(
      response => response.json(),
    ).then(
      data => {
        const trickTemplate = data.html
        if (data.html !== '') {
          homepageTricksList.innerHTML += trickTemplate
          this.dataset.page++
          if (data.message !== '') {
            this.innerText = data.message
            this.setAttribute('disabled', 'disabled')
          }
        }
      },
    ).then(
      () => homepageTricksList.style.opacity = '1',
    )
  }
}

export {
  getTricks,
}