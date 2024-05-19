import { ComponentFixture, TestBed } from '@angular/core/testing';

import { LoginCentrosComponent } from './login-centros.component';

describe('LoginCentrosComponent', () => {
  let component: LoginCentrosComponent;
  let fixture: ComponentFixture<LoginCentrosComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [LoginCentrosComponent],
    }).compileComponents();

    fixture = TestBed.createComponent(LoginCentrosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
