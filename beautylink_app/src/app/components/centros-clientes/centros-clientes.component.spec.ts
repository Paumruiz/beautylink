import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CentrosClientesComponent } from './centros-clientes.component';

describe('CentrosClientesComponent', () => {
  let component: CentrosClientesComponent;
  let fixture: ComponentFixture<CentrosClientesComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CentrosClientesComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(CentrosClientesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
