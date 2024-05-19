import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CentrosDashboardComponent } from './centros-dashboard.component';

describe('CentrosDashboardComponent', () => {
  let component: CentrosDashboardComponent;
  let fixture: ComponentFixture<CentrosDashboardComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CentrosDashboardComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(CentrosDashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
