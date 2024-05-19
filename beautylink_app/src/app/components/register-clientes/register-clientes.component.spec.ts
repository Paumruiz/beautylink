import { ComponentFixture, TestBed } from '@angular/core/testing';

import { RegisterClientesComponent } from './register-clientes.component';

describe('RegisterClientesComponent', () => {
  let component: RegisterClientesComponent;
  let fixture: ComponentFixture<RegisterClientesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [RegisterClientesComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(RegisterClientesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
