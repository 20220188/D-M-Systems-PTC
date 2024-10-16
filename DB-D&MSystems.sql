DROP DATABASE if EXISTS DB_DMSystems;

CREATE DATABASE DB_DMSystems;

USE DB_DMSystems;


/* tablas de inicio de session */

CREATE TABLE tb_niveles_usuarios(
id_nivel_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
tipo_usuario VARCHAR(20)
);

CREATE TABLE tb_usuarios(
id_usuario INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
usuario VARCHAR(20) unique,
clave VARCHAR (100) UNIQUE NOT NULL,
correo VARCHAR(50)UNIQUE NOT NULL,
nombre VARCHAR(25),
DUI VARCHAR (10)UNIQUE NOT NULL,
telefono VARCHAR(10) UNIQUE,
fecha_registro DATE DEFAULT(NOW()),
intentos_fallidos INT DEFAULT 0,
ultimo_cambio_clave DATE DEFAULT(NOW()),
tiempo_bloqueo DATETIME DEFAULT NULL,
id_nivel_usuario INT,
CONSTRAINT fk_usuarios_niveles_usuarios
FOREIGN KEY (id_nivel_usuario)
REFERENCES tb_niveles_usuarios (id_nivel_usuario)
);

INSERT INTO tb_niveles_usuarios (id_nivel_usuario, tipo_usuario)
VALUES 
(1, 'administrador'),
(2, 'inventario'),
(3, 'ventas'),
(4, 'caja');

/*INSERT INTO tb_usuarios (usuario, clave, correo, nombre, DUI, telefono, id_nivel_usuario)
VALUES ('usuario1', '$2y$10$332eJekvWPIyZgDplzJYRux9LNmyYcdF3rd2kmZ0bdaU.BbE1MJ7S', 'usuario1@example.com', 'Juan Perez', '01234567-8', '22223333', 1);

INSERT INTO tb_usuarios (usuario, clave, correo, nombre, DUI, telefono, id_nivel_usuario)
VALUES ('usuario2', '$2y$10$332eJekvWPIyZgDplzJYRux9LNmyYcdF3rd2kmZ0bdaU.BbE1MJ7S', 'usuario2@example.com', 'JuanPijita', '01234567-9', '22223333', 2);

INSERT INTO tb_usuarios (usuario, clave, correo, nombre, DUI, telefono, id_nivel_usuario)
VALUES ('usuario3', '$2y$10$332eJekvWPIyZgDplzJYRux9LNmyYcdF3rd2kmZ0bdaU.BbE1MJ7S', 'usuario3@example.com', 'Juan Perez', '01234567-7', '22223333', 3);

INSERT INTO tb_usuarios (usuario, clave, correo, nombre, DUI, telefono, id_nivel_usuario)
VALUES ('usuario4', '$2y$10$332eJekvWPIyZgDplzJYRux9LNmyYcdF3rd2kmZ0bdaU.BbE1MJ7S', 'usuario4@example.com', 'Juan Perez', '01234567-6', '22223333', 4);
*/ 

select * from tb_usuarios;

CREATE TABLE tb_clientes( 
id_cliente INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
nombre VARCHAR(50),
NIT VARCHAR(20) UNIQUE NULL,
NRC VARCHAR(10) NULL,
tipo VARCHAR(30) NULL,
nombre_comercial VARCHAR(50) NULL,
codigo VARCHAR(20)UNIQUE not NULL,
direccion VARCHAR(200) NULL,
telefono VARCHAR(9) UNIQUE NULL,
correo VARCHAR(30)UNIQUE
);

CREATE TABLE tb_categorias(
id_categoria INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
categoria VARCHAR(30) unique,
descrpicion VARCHAR(100) NULL
);


CREATE TABLE tb_iva(
id_iva INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
porcentaje INT NOT NULL
CHECK (porcentaje >0) 
);

CREATE TABLE tb_puntos_venta(
id_punto_venta INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
punto_venta VARCHAR(10),
clave VARCHAR (100) UNIQUE NOT NULL
);

CREATE TABLE tb_detalle_punto_ventas(
id_detalle_pventa INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
id_punto_venta INT,
CONSTRAINT fk_detalle_punto_venta
FOREIGN KEY (id_punto_venta)
REFERENCES tb_puntos_venta (id_punto_venta),
id_usuario INT,
CONSTRAINT fk_detalle_usuario
FOREIGN KEY (id_usuario)
REFERENCES tb_usuarios (id_usuario)
);


CREATE TABLE tb_sub_categorias(
id_sub_categoria INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
descripcion VARCHAR(30) NULL,
id_categoria INT,
CONSTRAINT fk_categoria_sub_categoria
FOREIGN KEY (id_categoria)
REFERENCES tb_categorias (id_categoria)
);

CREATE TABLE tb_laboratorios(
id_laboratorio INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
codigo INT UNIQUE,
nombre_laboratorio VARCHAR(50)
);


CREATE TABLE tb_dependientes(
id_dependiente INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
codigo INT UNIQUE,
nombre_dependiente VARCHAR(50)
);


CREATE TABLE tb_proveedores (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    codigo_proveedor VARCHAR(255) UNIQUE,
    nombre_proveedor VARCHAR(255),
    pais_proveedor VARCHAR(255),
    giro_negocio_proveedor VARCHAR(255),
    dui_proveedor VARCHAR(255) UNIQUE,
    nombre_comercial_proveedor VARCHAR(255) unique,
    fecha_proveedor DATE,
    nit_proveedor VARCHAR(255)UNIQUE,
    telefono_proveedor VARCHAR(255) UNIQUE,
    contacto_proveedor VARCHAR(255) UNIQUE,
    direccion_proveedor VARCHAR(255) UNIQUE,
    departamento_proveedor VARCHAR(255),
    municipio_proveedor VARCHAR(255)
);



CREATE TABLE tb_productos(
    id_producto INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    imagen VARCHAR(30),
    codigo varchar(15) UNIQUE,
    nombre VARCHAR(250),
    descripcion VARCHAR(250),
    fecha_vencimiento DATE,
    presentacion VARCHAR(25)
);

SELECT * FROM tb_productos;

	
CREATE TABLE tb_detalle_productos(
    /*campos de detalles de productos*/
    id_detalle_producto INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    ubicacion VARCHAR(250),
	minimo INT,
   maximo INT,
	marca VARCHAR(50),
	periodo_existencia DATE,
	fecha DATE,
	existencia INT CHECK(existencia >=0),
	id_laboratorio INT,
    CONSTRAINT fk_productos_laboratorio
    FOREIGN KEY (id_laboratorio)
    REFERENCES tb_laboratorios (id_laboratorio),
    /*Campos de detalles de precio*/
    descuento INT NULL CHECK (descuento >= 0),
    precio_con_descuento FLOAT CHECK(precio_con_descuento >=0),
    precio_opcional1 FLOAT CHECK(precio_opcional1 >=0),
    precio_opcional2 FLOAT CHECK(precio_opcional2 >=0),
    precio_opcional3 FLOAT CHECK(precio_opcional3 >=0),
    precio_opcional4 FLOAT CHECK(precio_opcional4 >=0),
    id_producto INT,
    CONSTRAINT fk_productos_detalle
    FOREIGN KEY (id_producto)
    REFERENCES tb_productos (id_producto),
    precio_sin_iva FLOAT CHECK (precio_sin_iva > 0),
    precio_con_iva FLOAT CHECK (precio_con_iva > 0),
    costo_unitario FLOAT CHECK (costo_unitario > 0)
);

CREATE TABLE tb_formas_pago(
id_forma_pago INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
forma_pago VARCHAR(100)
);

INSERT INTO tb_formas_pago(forma_pago)
VALUES('Efectivo'),
('Transferencia Bancaria');

CREATE TABLE tb_bodegas(
id_bodega INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
bodega VARCHAR(30)
);

INSERT INTO tb_bodegas(bodega)
VALUES('Bodega 1'), ('Bodega 2');

CREATE TABLE tb_documentos(
id_documento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
documento VARCHAR(40)
);

INSERT INTO tb_documentos(documento)
VALUES('Ccredito Fiscal'), ('Factura comercial');

CREATE TABLE tb_tipos_documento(
id_tipo_documento INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
tipo_documento VARCHAR(40)
);

INSERT INTO tb_tipos_documento(tipo_documento)
VALUES('Tipo 1'), ('Tipo 2');

CREATE TABLE tb_compras(
id_compra INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
factura VARCHAR(50) UNIQUE,
fecha DATETIME,
serie VARCHAR(50),
nota VARCHAR(250),
serie_persepcion VARCHAR(50),
NIT VARCHAR(20) UNIQUE,
id_producto INT,
CONSTRAINT fk_compras_Producto
FOREIGN KEY (id_producto)
REFERENCES tb_productos (id_producto),
id_forma_pago INT,
CONSTRAINT fk_compras_formapago
FOREIGN KEY (id_forma_pago)
REFERENCES tb_formas_pago (id_forma_pago),
id_bodega INT,
CONSTRAINT fk_compras_bodega
FOREIGN KEY (id_bodega)
REFERENCES tb_bodegas (id_bodega),
id_documento INT,
CONSTRAINT fk_compras_documento
FOREIGN KEY (id_documento)
REFERENCES tb_documentos (id_documento),
id_tipo_documento INT,
CONSTRAINT fk_compras_tipoDoc
FOREIGN KEY (id_tipo_documento)
REFERENCES tb_tipos_documento (id_tipo_documento)
);

/*
CREATE TABLE tb_detalle_compras(
id_detalle_compra INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
cantidad_producto INT CHECK (cantidad_producto >0),
fecha_ingreso DATE,
costo_compra INT,
id_producto INT,
CONSTRAINT fk_detalle_compra_producto
FOREIGN KEY (id_producto)
REFERENCES tb_productos (id_producto),
id_compra INT,
CONSTRAINT fk_detalle_compra_compras
FOREIGN KEY (id_compra)
REFERENCES tb_compras (id_compra)
);*/

CREATE TABLE tb_ventas (
    id_venta INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    fecha_venta DATE,
    id_dependiente INT,
    CONSTRAINT fk_venta_dependiente
    FOREIGN KEY (id_dependiente)
    REFERENCES tb_dependientes (id_dependiente),
    id_cliente INT,
    CONSTRAINT fk_venta_cliente
    FOREIGN KEY (id_cliente)
    REFERENCES tb_clientes (id_cliente),
    id_forma_pago INT,
    CONSTRAINT fk_venta_formapago
    FOREIGN KEY (id_forma_pago)
    REFERENCES tb_formas_pago (id_forma_pago),
    id_documento INT,
    CONSTRAINT fk_venta_documento
    FOREIGN KEY (id_documento)
    REFERENCES tb_documentos (id_documento),
    id_tipo_documento INT,
    CONSTRAINT fk_ventas_tipoDoc
    FOREIGN KEY (id_tipo_documento)
    REFERENCES tb_tipos_documento (id_tipo_documento),
    id_bodega INT,
    CONSTRAINT fk_venta_bodega
    FOREIGN KEY (id_bodega)
    REFERENCES tb_bodegas (id_bodega),
    notas VARCHAR(250),
    subtotal FLOAT null
);



CREATE TABLE tb_detalle_venta (
    id_detalle_venta INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_venta INT,
    CONSTRAINT fk_venta
    FOREIGN KEY (id_venta)
    REFERENCES tb_ventas (id_venta),
    id_producto INT,
    CONSTRAINT fk_detalle_venta_Producto
    FOREIGN KEY (id_producto)
    REFERENCES tb_productos (id_producto),
    cantidad INT,
    precio_con_iva FLOAT
);




CREATE TABLE tb_facturas(
id_factura_consumidor INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
fecha DATE,
id_cliente INT,
CONSTRAINT fk_cliente_consumidor_final
FOREIGN KEY (id_cliente)
REFERENCES tb_clientes (id_cliente),
id_detalle_pventa INT,
CONSTRAINT fk_factura_detalle_pventa
FOREIGN KEY (id_detalle_pventa)
REFERENCES tb_detalle_punto_ventas (id_detalle_pventa)
);

CREATE TABLE tb_detalles_factura(
id_detalle_factura INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
cantidad_producto INT CHECK (cantidad_producto>0),
precio_prdocuto FLOAT,
id_producto INT,
CONSTRAINT fk_detalle_facturas_productos
FOREIGN KEY (id_producto)
REFERENCES tb_productos (id_producto)
);


CREATE TABLE tb_notas_creditos( 
id_nota_credito INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
numero_docmuento INT,
fecha_documento DATE,
monto FLOAT,
id_producto INT,
CONSTRAINT fk_producto_notas_credito
FOREIGN KEY (id_producto)
REFERENCES tb_productos (id_producto),
id_detalle_factura INT,
CONSTRAINT fk_notas_credito_fiscal
FOREIGN KEY (id_detalle_factura)
REFERENCES tb_detalles_factura (id_detalle_factura)
);

CREATE TABLE tb_devoluciones_farmacia_proveedor(
id_devolucion_farmapro INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
descripcion_devolucion VARCHAR(250) NOT NULL,
id_nota_credito INT,
CONSTRAINT fk_notas_devoluciones_farmapro
FOREIGN KEY (id_nota_credito)
REFERENCES tb_notas_creditos (id_nota_credito)
);


CREATE TABLE tb_entradas(
id_entrada INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
nota VARCHAR(250),
fecha DATETIME,
tipo_entrada VARCHAR(50),
numero_entrada INT CHECK( numero_entrada>0),
id_detalle_producto INT,
CONSTRAINT fk_entradas_detalleProducto
FOREIGN KEY (id_detalle_producto)
REFERENCES tb_detalle_productos (id_detalle_producto)
);

CREATE TABLE tb_salidas(
id_salida INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
numero_salida INT,
fecha DATETIME,
entrega VARCHAR(50),
tipo_salida VARCHAR(50),
id_cliente INT,
CONSTRAINT fk_salidas_cliente
FOREIGN KEY (id_cliente)
REFERENCES tb_clientes (id_cliente),
id_dependiente INT,
CONSTRAINT fk_salidas_dependientes
FOREIGN KEY (id_dependiente)
REFERENCES tb_dependientes (id_dependiente),
nota VARCHAR(250),
id_detalle_producto INT,
CONSTRAINT fk_salidas_detalleProducto
FOREIGN KEY (id_detalle_producto)
REFERENCES tb_detalle_productos (id_detalle_producto),
cantidad_salida INT CHECK(cantidad_salida >0)
);

ALTER TABLE tb_usuarios 
ADD COLUMN codigo_2fa VARCHAR(6), 
ADD COLUMN expiracion_2fa DATETIME;

COMMIT;
