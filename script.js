let key = 'a09fe813101946ce8539a440b3a6416c';
let url = `https://newsapi.org/v2/top-headlines?country=us&apiKey=${key}`;
let newsContainer = document.getElementById('noticias');

fetch(url)
  .then((resp) => resp.json())
  .then((dato) => {
    console.log(dato);
    let noticias = dato.articles;

    noticias.map(function (numero) {
      let contenedor = document.createElement('div'); // CORREGIDO: usar <div>
      contenedor.classList.add("container_news"); // Agregamos clase CSS

      contenedor.innerHTML = `
        <div class="texto-noticia">
            <h1>${numero.title}</h1>
            <p>${numero.description || "Sin descripción disponible."}</p>
            <a href="${numero.url}" target="_blank">Leer más</a>
        </div>
        <div class="dic_img">
            <a href="${numero.url}" target="_blank">
                <img class="imagen-noticia" src="${numero.urlToImage || 'https://via.placeholder.com/400x200?text=Sin+Imagen'}" alt="Imagen de la noticia">
            </a>
        </div>
      `;

      newsContainer.appendChild(contenedor);
    });

    console.log(noticias);
  });
