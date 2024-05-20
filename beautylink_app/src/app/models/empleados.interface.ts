export interface Empleados {
  id: number;
  centro: number;
  nombre: string;
  apellidos: string;
  rol: string;
  horario_inicio: {
    date: string;
  };
  horario_fin: {
    date: string;
  };
}
