const ads = [
  {
    title: "Curso de Programación Gratis",
    image: "https://www.elestudiante.com.co/wp-content/uploads/2020/04/cursos-online-de-programacion.jpg",
    url: "https://www.freecodecamp.org/"
  },
  {
    title: "Ofertas en Electrónica",
    image: "https://i.blogs.es/a3fbe0/ofertas_eci_2/1366_2000.jpg",
    url: "https://www.amazon.com/"
  },
  {
    title: "Aprende Diseño Web",
    image: "https://th.bing.com/th/id/OIP.-zhXvUt7rciitFcCaOvk8QHaDt?r=0&rs=1&pid=ImgDetMainhttps://facialix.com/wp-content/uploads/2024/06/curso_javasript_poo_udemy_gratis.jpg",
    url: "https://www.codecademy.com/"
  },
  {
    title: "Domina JavaScript Hoy",
    image: "https://facialix.com/wp-content/uploads/2024/06/curso_javasript_poo_udemy_gratis.jpg",
    url: "https://developer.mozilla.org/es/docs/Web/JavaScript"
  },
  {
    title: "Mejora tus Habilidades en Excel",
    image: "https://protecsoluciones.cl/wp-content/uploads/2024/03/Mejorar-mis-habilidades-en-Excel.jpg",
    url: "https://www.microsoft.com/en-us/microsoft-365/excel"
  },
  {
    title: "Aprende Marketing Digital",
    image: "https://th.bing.com/th/id/OIP.2gD8jITm8TGIzf1u6dmVoAHaEK?r=0&rs=1&pid=ImgDetMain",
    url: "https://www.coursera.org/learn/marketing-digital"
  },
  {
    title: "Cursos Gratis de Fotografía",
    image: "https://www.plataformasdecursos.gratis/wp-content/uploads/Cursos-de-fotografia-gratuitos.png",
    url: "https://www.udemy.com/topic/photography/free/"
  },
  {
    title: "Herramientas para Freelancers",
    image: "https://user-images.strikinglycdn.com/res/hrscywv4p/image/upload/blog_service/2022-12-21-las-mejores-herramientas-para-freelancers-que-ayudan-a-gestionar-el-trabajo-increiblemente.jpg",
    url: "https://www.upwork.com/"
  },
  {
    title: "Aprende Python desde Cero",
    image: "https://inteligenciaartificialai.com/wp-content/uploads/2023/12/Aprende-Python-desde-cero-Guia-completa.png",
    url: "https://www.python.org/about/gettingstarted/"
  }
];

function renderAds() {
  const adsContainer = document.getElementById('ads');
  adsContainer.innerHTML = ''; // Limpiar antes de agregar

  ads.forEach(ad => {
    const adDiv = document.createElement('div');
    adDiv.className = 'ad-item';

    adDiv.innerHTML = `
      <img src="${ad.image}" alt="Anuncio: ${ad.title}">
      <div class="ad-title">${ad.title}</div>
      <a class="ad-link" href="${ad.url}" target="_blank" rel="noopener noreferrer">Ver más</a>
    `;

    adsContainer.appendChild(adDiv);
  });
}

document.addEventListener("DOMContentLoaded", renderAds);
