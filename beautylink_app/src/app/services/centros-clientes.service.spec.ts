import { TestBed } from '@angular/core/testing';

import { CentrosClientesService } from './centros-clientes.service';

describe('CentrosClientesService', () => {
  let service: CentrosClientesService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(CentrosClientesService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
