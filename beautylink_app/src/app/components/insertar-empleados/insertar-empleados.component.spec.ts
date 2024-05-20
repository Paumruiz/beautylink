import { ComponentFixture, TestBed } from '@angular/core/testing';

import { InsertarEmpleadosComponent } from './insertar-empleados.component';

describe('InsertarEmpleadosComponent', () => {
  let component: InsertarEmpleadosComponent;
  let fixture: ComponentFixture<InsertarEmpleadosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [InsertarEmpleadosComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(InsertarEmpleadosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
