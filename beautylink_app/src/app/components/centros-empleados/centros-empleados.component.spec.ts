import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CentrosEmpleadosComponent } from './centros-empleados.component';

describe('CentrosEmpleadosComponent', () => {
  let component: CentrosEmpleadosComponent;
  let fixture: ComponentFixture<CentrosEmpleadosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CentrosEmpleadosComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(CentrosEmpleadosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
