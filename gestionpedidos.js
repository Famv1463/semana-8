// Lista de promociones
const promociones = [
    {
        nombre: "Descuento Gamer",
        descripcion: "20% de descuento en teclados mecánicos."
    },
    {
        nombre: "Envío Gratis",
        descripcion: "En compras superiores a $50.000."
    },
    {
        nombre: "Oferta Flash",
        descripcion: "2x1 en accesorios seleccionados."
    }
];

// Mostrar promociones
function mostrarPromociones() {
    const contenedor = document.getElementById("promociones");

    promociones.forEach(promo => {
        const tarjeta = document.createElement("div");
        tarjeta.className = "promo";

        tarjeta.innerHTML = `
            <h3>${promo.nombre}</h3>
            <p>${promo.descripcion}</p>
        `;

        contenedor.appendChild(tarjeta);
    });
}

// Gestión de pedidos
function gestionarPedido(producto) {
    alert(`El pedido del producto "${producto}" fue registrado correctamente.`);
}

document.addEventListener("DOMContentLoaded", mostrarPromociones);
