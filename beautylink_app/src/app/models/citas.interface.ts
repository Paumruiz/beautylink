export class Citas {
  constructor(
    public id: number,
    public servicio: number,
    public precio_servicio: number,
    public cliente: number,
    public empleado: number,
    public centro: number,
    public fecha: string
  ) {}
}
