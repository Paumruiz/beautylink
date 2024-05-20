import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CentrosCitasComponent } from './centros-citas.component';

describe('CentrosCitasComponent', () => {
  let component: CentrosCitasComponent;
  let fixture: ComponentFixture<CentrosCitasComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [CentrosCitasComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(CentrosCitasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
