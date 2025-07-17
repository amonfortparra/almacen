El codigo creado por mi se encuentra en src y en .env la configuracion para la conexion con base de datos.

Las peticiones POST y Get son las siguientes:

POST - api/orders 
  body_data: 
    - number -> numero del pedido
    - list_products -> string de los id de productos separado por ","
    - date -> fecha actual, formato m/d/Y 

GET - api/pickers/{id_picker}

Sobre el metodo de ordenacion se considero que los pickers no usan maquinaria y tampoco un peso en los objetos, por lo que se ordenará la lista de productos de manera sencilla

El almacen se ordena por:

Estanterias 
Filas 
Columnas 

El metodo de ordenacion ordena la lista de productos primero por estanterias, luego de las 4 primeras filas ordena segun columna y luego fila, tras esto vuelve a ordenar las filas restantes de la misma manera. Se emplea usort para realizar esta ordenacion.
