import { TestBed } from '@angular/core/testing';

import { CentrosEmpleadosService } from './centros-empleados.service';

describe('CentrosEmpleadosService', () => {
  let service: CentrosEmpleadosService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(CentrosEmpleadosService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
