import { ComponentFixture, TestBed } from '@angular/core/testing';

import { InsertarCitaCentroComponent } from './insertar-cita-centro.component';

describe('InsertarCitaCentroComponent', () => {
  let component: InsertarCitaCentroComponent;
  let fixture: ComponentFixture<InsertarCitaCentroComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [InsertarCitaCentroComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(InsertarCitaCentroComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
