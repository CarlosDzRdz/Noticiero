let key = 'a09fe813101946ce8539a440b3a6416c';
let url = `https://newsapi.org/v2/top-headlines?country=us&apiKey=${key}`;
let newsContainer = document.getElementById('noticias');

fetch(url).then((resp) => resp.json()).then(dato => {
    console.log(dato);
    let noticias = (dato.articles);

    noticias.map(function(numero){
        let contenedor = document.createElement('noticias');
        contenedor.innerHTML = `<br>
                                <div class="container_news">   
                                    <div class="dic_img">
                                        <img class="imagen-noticia" style="max-width: 400px" src="${numero.urlToImage}"
                                    </div>

                                    <div class="texto-noticia">
                                        <h1>${numero.title}</h1>
                                        <p>${numero.description}</p>
                                        <a href="${numero.url}" target="_blank">Leer más</a>
                                    </div>
                                </div>
                                <br>`;
                                
        newsContainer.appendChild(contenedor);
        console.log(contenedor);
    })
    console.log(noticias);
});