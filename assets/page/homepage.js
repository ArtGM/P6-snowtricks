function getTricks () {
  const loadMoreButton = document.getElementById('loadMoreTricks')
  const homepageTricksList = document.getElementById('homeTricksList')

  loadMoreButton.onclick = function () {
    fetch(this.dataset.url).then(
      response => response.json(),
    ).then(
      data => {
        const trickTemplate = data.html
        homepageTricksList.innerHTML += trickTemplate
      },
    )
  }
}

export {
  getTricks,
}