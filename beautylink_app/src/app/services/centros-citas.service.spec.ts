import { TestBed } from '@angular/core/testing';

import { CentrosCitasService } from './centros-citas.service';

describe('CentrosCitasService', () => {
  let service: CentrosCitasService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(CentrosCitasService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
