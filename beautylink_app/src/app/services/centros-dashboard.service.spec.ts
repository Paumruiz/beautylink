import { TestBed } from '@angular/core/testing';

import { CentrosDashboardService } from './centros-dashboard.service';

describe('CentrosDashboardService', () => {
  let service: CentrosDashboardService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(CentrosDashboardService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
