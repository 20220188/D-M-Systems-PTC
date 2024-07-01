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
usuario VARCHAR(10),
clave VARCHAR(100),1
correo VARCHAR(50),
nombre VARCHAR(25),
DUI VARCHAR(10),
telefono VARCHAR(10),
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
nombre VARCHAR(15),
DUI VARCHAR(10),
telefono VARCHAR(9),
correo VARCHAR(50),
NIT VARCHAR(20) NULL,
NRC VARCHAR(10) NULL
);

CREATE TABLE tb_categorias(
id_categoria INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
categoria VARCHAR(30),
descrpicion VARCHAR(100) NULL
);


CREATE TABLE tb_iva(
id_iva INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
porcentaje INT NOT NULL
CHECK (porcentaje >0) 
);

CREATE TABLE tb_puntos_venta(
id_punto_venta INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
punto_venta VARCHAR(10)
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

CREATE TABLE tb_proveedores (
    id_proveedor INT AUTO_INCREMENT PRIMARY KEY,
    codigo_proveedor VARCHAR(255),
    nombre_proveedor VARCHAR(255),
    pais_proveedor VARCHAR(255),
    giro_negocio_proveedor VARCHAR(255),
    dui_proveedor VARCHAR(255),
    nombre_comercial_proveedor VARCHAR(255),
    fecha_proveedor DATE,
    nit_proveedor VARCHAR(255),
    telefono_proveedor VARCHAR(255),
    contacto_proveedor VARCHAR(255),
    direccion_proveedor VARCHAR(255),
    departamento_proveedor VARCHAR(255),
    municipio_proveedor VARCHAR(255)
);


CREATE TABLE tb_productos(
id_producto INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
codigo INT,
nombre VARCHAR(250),
id_proveedor INT,
CONSTRAINT fk_producto_proveedor
FOREIGN KEY (id_proveedor)
REFERENCES tb_proveedores (id_proveedor),
precio_sin_iva FLOAT,
CHECK (precio_sin_iva>0),
existencia INT
CHECK (existencia>=0),
descuento INT NULL
CHECK (descuento>=0),
id_sub_categoria INT,
CONSTRAINT fk_producto_sub_categoria
FOREIGN KEY (id_sub_categoria)
REFERENCES tb_sub_categorias (id_sub_categoria),
id_iva INT,
CONSTRAINT fk_producto_iva
FOREIGN KEY (id_iva)
REFERENCES tb_iva (id_iva)
);


CREATE TABLE tb_compras(
id_compra INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
id_usuario INT,
CONSTRAINT fk_compra_usuario
FOREIGN KEY (id_usuario)
REFERENCES tb_usuarios (id_usuario)
);

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
);

CREATE TABLE tb_ventas(
id_venta INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
monto_venta FLOAT,
fecha_venta DATE,
id_cliente INT,
CONSTRAINT fk_venta_cliente
FOREIGN KEY (id_cliente)
REFERENCES tb_clientes (id_cliente),
id_producto INT,
CONSTRAINT fk_venta_producto
FOREIGN KEY (id_producto)
REFERENCES tb_productos (id_producto)
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