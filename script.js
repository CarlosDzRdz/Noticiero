let key = 'a09fe813101946ce8539a440b3a6416c';
let newsContainer = document.getElementById('noticias');

// Configuración de categorías con sus títulos personalizados
const categorias = [
    { categoria: 'entertainment', titulo: 'Arts & Lifestyle' },
    { categoria: 'business', titulo: 'Business' },
    { categoria: 'health', titulo: 'Health' },
    { categoria: 'science', titulo: 'Science' },
    { categoria: 'sports', titulo: 'Sports' },
    { categoria: 'technology', titulo: 'Technology' }
];

// Función para cargar noticias principales
function cargarNoticiasPrincipales() {
    let url = `https://newsapi.org/v2/top-headlines?country=us&apiKey=${key}`;
    
    fetch(url)
        .then((resp) => resp.json())
        .then((dato) => {
            console.log('Noticias principales:', dato);
            let noticias = dato.articles;
            
            // Limitar a solo las primeras 3 noticias
            let noticiaPrincipales = noticias.slice(0, 3);

            noticiaPrincipales.forEach(function (numero) {
                let contenedor = document.createElement('div');
                contenedor.classList.add("container_news");

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

            console.log('Noticias principales cargadas:', noticiaPrincipales);
        })
        .catch(error => {
            console.error('Error al cargar noticias principales:', error);
        });
}

// Función genérica para cargar noticias por categoría
function cargarNoticiasPorCategoria(categoria, titulo) {
    let url = `https://newsapi.org/v2/top-headlines?country=us&category=${categoria}&apiKey=${key}`;
    
    return fetch(url)
        .then((resp) => resp.json())
        .then((dato) => {
            console.log(`Noticias de ${categoria}:`, dato);
            let noticias = dato.articles;
            
            // Tomar solo las primeras 3 noticias de la categoría
            let noticiasCategoria = noticias.slice(0, 3);

            // Crear contenedor para la sección de categoría
            let seccionCategoria = document.createElement('div');
            seccionCategoria.classList.add('seccion-categoria');
            seccionCategoria.id = `categoria-${categoria}`; // Agregar ID para las anclas
            
            // Título de la sección
            let tituloSeccion = document.createElement('h2');
            tituloSeccion.textContent = titulo;
            tituloSeccion.classList.add('titulo-categoria');
            seccionCategoria.appendChild(tituloSeccion);
            
            // Contenedor para las 3 noticias en fila
            let contenedorFilas = document.createElement('div');
            contenedorFilas.classList.add('noticias-categoria-grid');
            
            noticiasCategoria.forEach(function(noticia) {
                let tarjetaNoticia = document.createElement('div');
                tarjetaNoticia.classList.add('tarjeta-categoria');
                
                tarjetaNoticia.innerHTML = `
                    <a href="${noticia.url}" target="_blank">
                        <img src="${noticia.urlToImage || 'https://via.placeholder.com/300x200?text=Sin+Imagen'}" alt="Imagen de la noticia">
                    </a>
                    <div class="contenido-tarjeta">
                        <small class="etiqueta-categoria">${titulo}</small>
                        <h3><a href="${noticia.url}" target="_blank">${noticia.title}</a></h3>
                    </div>
                `;
                
                contenedorFilas.appendChild(tarjetaNoticia);
            });
            
            seccionCategoria.appendChild(contenedorFilas);
            newsContainer.appendChild(seccionCategoria);
            
            console.log(`Noticias de ${categoria} cargadas:`, noticiasCategoria);
        })
        .catch(error => {
            console.error(`Error al cargar noticias de ${categoria}:`, error);
        });
}

// Función para cargar todas las categorías de forma secuencial
async function cargarTodasLasCategorias() {
    for (let i = 0; i < categorias.length; i++) {
        const { categoria, titulo } = categorias[i];
        await cargarNoticiasPorCategoria(categoria, titulo);
        
        // Pequeña pausa entre cada categoría para evitar sobrecargar la API
        await new Promise(resolve => setTimeout(resolve, 500));
    }
}

// Función principal para cargar todo el contenido
async function inicializarPagina() {
    try {
        // Primero cargar noticias principales
        await cargarNoticiasPrincipales();
        
        // Esperar un poco antes de cargar categorías
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // Cargar todas las categorías
        await cargarTodasLasCategorias();
        
        console.log('Todas las noticias han sido cargadas exitosamente');
    } catch (error) {
        console.error('Error al inicializar la página:', error);
    }
}

// Inicializar cuando se carga la página
document.addEventListener('DOMContentLoaded', inicializarPagina);